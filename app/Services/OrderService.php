<?php

namespace App\Services;

use App\Models\User;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMutation;
use App\Exceptions\StockException;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Create order(s) from cart items (split orders by shop).
     *
     * @param User $customer
     * @param iterable $cartItems
     * @param Address $address
     * @param string $courier
     * @return array Array of created Order models.
     * @throws StockException
     */
    public function createOrder(User $customer, $cartItems, Address $address, string $courier): array
    {
        return DB::transaction(function () use ($customer, $cartItems, $address, $courier) {
            $createdOrders = [];

            // Group cart items by shop, as orders are split by shop
            $groupedItems = collect($cartItems)->groupBy('product.shop_id');

            foreach ($groupedItems as $shopId => $items) {
                $totalAmount = 0;
                $totalWeight = 0;

                // 1. Lock and validate stock for all products in this order
                foreach ($items as $item) {
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->firstOrFail();
                    if ($product->stock < $item->qty) {
                        throw new StockException("Stok produk \"{$product->name}\" tidak mencukupi.");
                    }

                    $totalAmount += $product->price * $item->qty;
                    $totalWeight += $product->weight * $item->qty;
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
                    'customer_id'      => $customer->id,
                    'shop_id'          => $shopId,
                    'total_amount'     => $totalAmount,
                    'shipping_cost'    => $shippingCost,
                    'grand_total'      => $grandTotal,
                    'status'           => 'pending_payment',
                    'shipping_address' => "Penerima: {$address->recipient_name}\nTelepon: {$address->phone}\nAlamat: {$address->address_line}",
                    'courier'          => $courier,
                ]);

                foreach ($items as $item) {
                    $product = Product::findOrFail($item->product_id); // already locked

                    $order->items()->create([
                        'product_id' => $item->product_id,
                        'qty'        => $item->qty,
                        'price'      => $product->price,
                        'subtotal'   => $product->price * $item->qty,
                    ]);

                    // Decrement product stock
                    $product->decrement('stock', $item->qty);

                    // Create stock mutation log
                    StockMutation::create([
                        'product_id'  => $item->product_id,
                        'qty'         => $item->qty,
                        'type'        => 'OUT',
                        'description' => 'Pembelian via Invoice #' . $invoiceNumber,
                    ]);

                    // Delete the item from cart
                    $item->delete();
                }

                $createdOrders[] = $order;
            }

            return $createdOrders;
        });
    }

    /**
     * Confirm receipt of goods (marks order as completed).
     *
     * @param Order $order
     * @return Order
     */
    public function completeOrder(Order $order): Order
    {
        abort_if($order->status !== 'shipped', 403, 'Pesanan belum dikirim.');

        $order->update(['status' => 'completed']);

        return $order;
    }

    /**
     * Accept a pending confirmation order (set to processing and mark payment as paid).
     *
     * @param Order $order
     * @return Order
     */
    public function acceptOrder(Order $order): Order
    {
        abort_if($order->status !== 'pending_confirmation', 403, 'Status pesanan tidak valid.');

        $order->update(['status' => 'processing']);

        if ($order->payment) {
            $order->payment->update([
                'payment_status' => 'paid',
                'paid_at'        => now()
            ]);
        }

        return $order;
    }

    /**
     * Mark order as shipped and record the tracking number.
     *
     * @param Order $order
     * @param string $trackingNumber
     * @return Order
     */
    public function shipOrder(Order $order, string $trackingNumber): Order
    {
        abort_if($order->status !== 'processing', 403, 'Status pesanan tidak valid.');

        $order->update([
            'status'          => 'shipped',
            'tracking_number' => $trackingNumber,
        ]);

        return $order;
    }

    /**
     * Cancel an order and restore product stock via stock mutation.
     *
     * @param Order $order
     * @return Order
     */
    public function cancelOrder(Order $order): Order
    {
        abort_if(! in_array($order->status, ['pending_confirmation', 'processing']), 403, 'Pesanan tidak dapat dibatalkan.');

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->qty);
                StockMutation::create([
                    'product_id'  => $item->product_id,
                    'qty'         => $item->qty,
                    'type'        => 'IN',
                    'description' => 'Pembatalan Pesanan #' . $order->invoice_number,
                ]);
            }

            $order->update(['status' => 'cancelled']);

            if ($order->payment) {
                $order->payment->update(['payment_status' => 'failed']);
            }
        });

        return $order;
    }
}
