<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\UploadedFile;

class PaymentService
{
    /**
     * Store uploaded payment proof and update order/payment status.
     *
     * @param Order $order
     * @param string $paymentMethod
     * @param UploadedFile $proofFile
     * @return void
     */
    public function submitPayment(Order $order, string $paymentMethod, UploadedFile $proofFile): void
    {
        abort_if($order->status !== 'pending_payment', 403, 'Status pesanan tidak sesuai untuk pembayaran.');

        $path = $proofFile->store('payments', 'public');

        $order->payment()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'payment_method'   => $paymentMethod,
                'payment_status'   => 'pending',
                'amount_paid'      => $order->grand_total,
                'proof_of_payment' => $path,
            ]
        );

        $order->update(['status' => 'pending_confirmation']);
    }
}
