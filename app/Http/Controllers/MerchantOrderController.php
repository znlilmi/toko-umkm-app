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
    public function accept(Order $order, \App\Services\OrderService $orderService): RedirectResponse
    {
        $shop = $this->getShop();
        abort_if($order->shop_id !== $shop->id, 403);

        $orderService->acceptOrder($order);

        return redirect()->route('merchant.orders.show', $order)
            ->with('success', 'Pesanan diterima dan sedang diproses.');
    }

    /**
     * Mark order as shipped and record the tracking number.
     */
    public function ship(Request $request, Order $order, \App\Services\OrderService $orderService): RedirectResponse
    {
        $shop = $this->getShop();
        abort_if($order->shop_id !== $shop->id, 403);

        $request->validate([
            'tracking_number' => ['required', 'string', 'max:255'],
        ]);

        $orderService->shipOrder($order, $request->tracking_number);

        return redirect()->route('merchant.orders.show', $order)
            ->with('success', 'Pesanan ditandai sebagai dikirim.');
    }

    /**
     * Cancel an order and restore product stock via stock mutation.
     */
    public function cancel(Order $order, \App\Services\OrderService $orderService): RedirectResponse
    {
        $shop = $this->getShop();
        abort_if($order->shop_id !== $shop->id, 403);

        $orderService->cancelOrder($order);

        return redirect()->route('merchant.orders.index')
            ->with('success', 'Pesanan berhasil dibatalkan dan stok telah dipulihkan.');
    }
}
