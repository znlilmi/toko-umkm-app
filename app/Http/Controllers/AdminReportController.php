<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class AdminReportController extends Controller
{
    /**
     * Download platform commission and merchant performance report.
     */
    public function commissionPdf()
    {
        // Restrict to admin only
        abort_if(auth()->user()->role !== 'admin', 403, 'Akses ditolak.');

        $shops = Shop::with('user')->get();

        $merchantData = [];
        $totalPlatformGmv = 0;
        $totalPlatformCommission = 0;
        $totalCompletedOrdersCount = 0;

        foreach ($shops as $shop) {
            // Get completed orders for this shop
            $completedOrders = Order::where('shop_id', $shop->id)
                ->where('status', 'completed')
                ->get();

            $completedCount = $completedOrders->count();
            $gmv = $completedOrders->sum('grand_total'); // Using grand_total (or total_amount, let's use grand_total as GMV)
            $commission = $gmv * 0.05; // 5% platform commission rate as defined in seeder/specification

            $totalCompletedOrdersCount += $completedCount;
            $totalPlatformGmv += $gmv;
            $totalPlatformCommission += $commission;

            $merchantData[] = [
                'name' => $shop->name,
                'owner' => $shop->user->name ?? 'Pemilik Tidak Diketahui',
                'joined_date' => $shop->created_at->format('d M Y'),
                'orders_count' => $completedCount,
                'gmv' => $gmv,
                'commission' => $commission,
                'status' => $shop->status,
            ];
        }

        $pdf = Pdf::loadView('pdf.admin_commission', [
            'merchantData' => $merchantData,
            'totalShops' => $shops->count(),
            'totalCompletedOrdersCount' => $totalCompletedOrdersCount,
            'totalPlatformGmv' => $totalPlatformGmv,
            'totalPlatformCommission' => $totalPlatformCommission,
            'date' => Carbon::now()
        ]);

        return $pdf->download('laporan-komisi-platform.pdf');
    }
}
