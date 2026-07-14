<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MerchantReportController extends Controller
{
    private function getShop()
    {
        $shop = auth()->user()->shop;
        abort_if(! $shop, 404, 'Toko tidak ditemukan.');

        return $shop;
    }

    /**
     * Download sales report for merchant's shop within a date range.
     */
    public function salesPdf(Request $request)
    {
        $shop = $this->getShop();

        // Support both start/end and start_date/end_date query parameters
        $startDateStr = $request->get('start') ?? $request->get('start_date');
        $endDateStr = $request->get('end') ?? $request->get('end_date');

        $startDate = $startDateStr ? Carbon::parse($startDateStr)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $endDate = $endDateStr ? Carbon::parse($endDateStr)->endOfDay() : Carbon::now()->endOfDay();

        $orders = Order::where('shop_id', $shop->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['customer', 'items.product'])
            ->orderBy('created_at', 'asc')
            ->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalShipping = $orders->sum('shipping_cost');
        $grandTotal = $orders->sum('grand_total');
        $totalOrdersCount = $orders->count();

        $totalQty = 0;
        foreach ($orders as $order) {
            $totalQty += $order->items->sum('qty');
        }

        $pdf = Pdf::loadView('pdf.sales_report', [
            'shop' => $shop,
            'orders' => $orders,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalRevenue' => $totalRevenue,
            'totalShipping' => $totalShipping,
            'grandTotal' => $grandTotal,
            'totalOrdersCount' => $totalOrdersCount,
            'totalQty' => $totalQty
        ]);

        return $pdf->download('laporan-penjualan-' . $shop->slug . '.pdf');
    }

    /**
     * Download critical low stock report.
     */
    public function lowStockPdf()
    {
        $shop = $this->getShop();

        $products = Product::where('shop_id', $shop->id)
            ->where('stock', '<=', 5)
            ->with('categories')
            ->orderBy('stock', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.low_stock', [
            'shop' => $shop,
            'products' => $products,
            'date' => Carbon::now()
        ]);

        return $pdf->download('laporan-stok-kritis-' . $shop->slug . '.pdf');
    }
}
