<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Download the invoice for the given order as PDF.
     */
    public function download(Order $order)
    {
        $user = auth()->user();
        
        // Authorize access: customer, shop owner (merchant), or admin
        $isAuthorized = $user->role === 'admin' 
            || $user->id === $order->customer_id 
            || ($user->role === 'merchant' && $order->shop && $order->shop->user_id === $user->id);
            
        abort_if(! $isAuthorized, 403, 'Anda tidak memiliki akses ke invoice ini.');

        $order->load(['shop', 'items.product', 'customer', 'payment']);

        $pdf = Pdf::loadView('pdf.invoice', compact('order'));

        return $pdf->download('invoice-' . str_replace('/', '-', $order->invoice_number) . '.pdf');
    }
}
