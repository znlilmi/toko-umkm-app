<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display the authenticated customer's order history.
     */
    public function index(): View
    {
        $orders = auth()->user()
            ->orders()
            ->with(['shop', 'items.product', 'payment'])
            ->latest()
            ->paginate(15);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the checkout form (select address & courier from cart items).
     */
    public function create(): View
    {
        $cartItems = auth()->user()->carts()->with('product.shop')->get();
        $addresses = auth()->user()->addresses()->get();

        return view('orders.create', compact('cartItems', 'addresses'));
    }

    /**
     * Place the order (checkout), grouped by shop.
     */
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $address = $user->addresses()->findOrFail($request->address_id);

        $cartItems = $user->carts()
            ->whereIn('id', $request->cart_item_ids)
            ->with('product.shop')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda kosong atau item tidak ditemukan.');
        }

        // Group cart items by shop, as orders are split by shop
        $groupedItems = $cartItems->groupBy('product.shop_id');

        \Illuminate\Support\Facades\DB::transaction(function () use ($user, $address, $request, $groupedItems) {
            foreach ($groupedItems as $shopId => $items) {
                $totalAmount = 0;
                $totalWeight = 0;

                foreach ($items as $item) {
                    $totalAmount += $item->product->price * $item->qty;
                    $totalWeight += $item->product->weight * $item->qty;
                }

                // Shipping cost formula: Rp 15.000 per kg flat, minimum 15.000
                $shippingCost = ceil($totalWeight / 1000) * 15000;
                if ($shippingCost == 0) {
                    $shippingCost = 15000;
                }

                $grandTotal = $totalAmount + $shippingCost;

                // Create unique invoice number
                $invoiceNumber = 'INV/' . date('Ymd') . '/' . strtoupper(bin2hex(random_bytes(4)));

                $order = Order::create([
                    'invoice_number'   => $invoiceNumber,
                    'customer_id'      => $user->id,
                    'shop_id'          => $shopId,
                    'total_amount'     => $totalAmount,
                    'shipping_cost'    => $shippingCost,
                    'grand_total'      => $grandTotal,
                    'status'           => 'pending_payment',
                    'shipping_address' => "Penerima: {$address->recipient_name}\nTelepon: {$address->phone}\nAlamat: {$address->address_line}",
                    'courier'          => $request->courier,
                ]);

                foreach ($items as $item) {
                    $order->items()->create([
                        'product_id' => $item->product_id,
                        'qty'        => $item->qty,
                        'price'      => $item->product->price,
                        'subtotal'   => $item->product->price * $item->qty,
                    ]);

                    // Decrement product stock
                    $item->product->decrement('stock', $item->qty);

                    // Delete the item from cart
                    $item->delete();
                }
            }
        });

        return redirect()->route('orders.index')
            ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
    }

    /**
     * Display the order tracking/detail page.
     */
    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        $order->load(['shop', 'items.product', 'payment', 'items.review']);

        return view('orders.show', compact('order'));
    }

    /**
     * Customer confirms receipt of goods (marks order as completed).
     */
    public function complete(Order $order): RedirectResponse
    {
        $this->authorize('update', $order);

        abort_if($order->status !== 'shipped', 403, 'Pesanan belum dikirim.');

        $order->update(['status' => 'completed']);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pesanan dikonfirmasi selesai. Terima kasih!');
    }
}
