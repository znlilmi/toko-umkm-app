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

    /**
     * Display merchant performance dashboard comparing sales in the last 3 months.
     */
    public function merchantPerformance()
    {
        // Restrict to admin only
        abort_if(auth()->user()->role !== 'admin', 403, 'Akses ditolak.');

        $startDate = Carbon::now()->subMonths(2)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        // Get all shops with owners
        $shops = Shop::with('user')->get();

        // Establish the last 3 months in Y-m format
        $months = [];
        $monthLabels = [];
        $monthNamesIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        for ($i = 2; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('Y-m');
            $monthLabels[] = $monthNamesIndo[$date->month] . ' ' . $date->year;
        }

        // Query orders grouped by shop and month
        $orderData = \Illuminate\Support\Facades\DB::table('orders')
            ->selectRaw('shop_id, DATE_FORMAT(created_at, "%Y-%m") as month, SUM(grand_total) as revenue, COUNT(*) as orders_count')
            ->where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('shop_id', 'month')
            ->get();

        // Structure merchant sales matrix
        $merchantPerformance = [];
        foreach ($shops as $shop) {
            $monthlyRevenue = [];
            $totalRevenue = 0;
            $totalOrders = 0;

            foreach ($months as $month) {
                $found = $orderData->where('shop_id', $shop->id)->where('month', $month)->first();
                $rev = $found ? (float) $found->revenue : 0.0;
                $cnt = $found ? (int) $found->orders_count : 0;

                $monthlyRevenue[$month] = $rev;
                $totalRevenue += $rev;
                $totalOrders += $cnt;
            }

            $merchantPerformance[] = [
                'id' => $shop->id,
                'name' => $shop->name,
                'owner' => $shop->user->name ?? 'Pemilik Tidak Diketahui',
                'monthly_revenue' => $monthlyRevenue,
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
            ];
        }

        // Sort by total revenue desc
        usort($merchantPerformance, function ($a, $b) {
            return $b['total_revenue'] <=> $a['total_revenue'];
        });

        // Setup chart datasets
        // We want a list of shop names for the chart labels
        $chartLabels = array_map(fn($item) => $item['name'], $merchantPerformance);

        // Datasets: 3 datasets, one for each month
        $chartDatasets = [];
        $colors = [
            'rgba(99, 102, 241, 0.85)', // Indigo
            'rgba(249, 115, 22, 0.85)', // Orange
            'rgba(16, 185, 129, 0.85)', // Emerald
        ];
        $borderColors = [
            '#6366f1',
            '#f97316',
            '#10b981',
        ];

        foreach ($months as $idx => $month) {
            $data = [];
            foreach ($merchantPerformance as $perf) {
                $data[] = $perf['monthly_revenue'][$month];
            }

            $chartDatasets[] = [
                'label' => $monthLabels[$idx],
                'data' => $data,
                'backgroundColor' => $colors[$idx],
                'borderColor' => $borderColors[$idx],
                'borderWidth' => 1.5,
                'borderRadius' => 6,
            ];
        }

        return view('admin.reports.merchant_performance', compact(
            'merchantPerformance',
            'monthLabels',
            'months',
            'chartLabels',
            'chartDatasets'
        ));
    }
}
