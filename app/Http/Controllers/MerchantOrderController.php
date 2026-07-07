<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\StockMutation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MerchantOrderController extends Controller
{
    private function getShop()
    {
        $shop = auth()->user()->shop;
        abort_if(! $shop, 404, 'Toko tidak ditemukan.');

        return $shop;
    }

    /**
     * Display incoming orders for the merchant's shop.
     */
    public function index(Request $request): View
    {
        $shop   = $this->getShop();
        $status = $request->get('status');

        $orders = $shop->orders()
            ->with(['customer', 'items.product', 'payment'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20);

        return view('merchant.orders.index', compact('orders', 'status'));
    }

    /**
     * Display a single order detail for the merchant.
     */
    public function show(Order $order): View
    {
        $shop = $this->getShop();
        abort_if($order->shop_id !== $shop->id, 403);

        $order->load(['customer', 'items.product', 'payment']);

        return view('merchant.orders.show', compact('order'));
    }

    /**
     * Accept a pending_confirmation order → set to processing.
     */
    public function accept(Order $order): RedirectResponse
    {
        $shop = $this->getShop();
        abort_if($order->shop_id !== $shop->id, 403);
        abort_if($order->status !== 'pending_confirmation', 403, 'Status pesanan tidak valid.');

        $order->update(['status' => 'processing']);

        if ($order->payment) {
            $order->payment->update(['payment_status' => 'paid', 'paid_at' => now()]);
        }

        return redirect()->route('merchant.orders.show', $order)
            ->with('success', 'Pesanan diterima dan sedang diproses.');
    }

    /**
     * Mark order as shipped and record the tracking number.
     */
    public function ship(Request $request, Order $order): RedirectResponse
    {
        $shop = $this->getShop();
        abort_if($order->shop_id !== $shop->id, 403);
        abort_if($order->status !== 'processing', 403, 'Status pesanan tidak valid.');

        $request->validate([
            'tracking_number' => ['required', 'string', 'max:255'],
        ]);

        $order->update([
            'status'          => 'shipped',
            'tracking_number' => $request->tracking_number,
        ]);

        return redirect()->route('merchant.orders.show', $order)
            ->with('success', 'Pesanan ditandai sebagai dikirim.');
    }

    /**
     * Cancel an order and restore product stock via stock mutation.
     */
    public function cancel(Order $order): RedirectResponse
    {
        $shop = $this->getShop();
        abort_if($order->shop_id !== $shop->id, 403);
        abort_if(! in_array($order->status, ['pending_confirmation', 'processing']), 403, 'Pesanan tidak dapat dibatalkan.');

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

        return redirect()->route('merchant.orders.index')
            ->with('success', 'Pesanan berhasil dibatalkan dan stok telah dipulihkan.');
    }
}
