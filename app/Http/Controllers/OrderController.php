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
        // Business logic delegated to OrderService in a future implementation.
        // Placeholder redirect for now.
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
