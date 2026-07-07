<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Show the payment upload form for a given order.
     */
    public function create(Order $order): View
    {
        $this->authorize('view', $order);

        abort_if($order->status !== 'pending_payment', 403, 'Status pesanan tidak sesuai untuk pembayaran.');

        return view('payments.create', compact('order'));
    }

    /**
     * Store uploaded payment proof and update order/payment status.
     */
    public function store(StorePaymentRequest $request, Order $order): RedirectResponse
    {
        $this->authorize('view', $order);

        abort_if($order->status !== 'pending_payment', 403, 'Status pesanan tidak sesuai untuk pembayaran.');

        $path = $request->file('proof_of_payment')->store('payments', 'public');

        $order->payment()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'payment_method'   => $request->payment_method,
                'payment_status'   => 'pending',
                'amount_paid'      => $order->grand_total,
                'proof_of_payment' => $path,
            ]
        );

        $order->update(['status' => 'pending_confirmation']);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu konfirmasi.');
    }
}
