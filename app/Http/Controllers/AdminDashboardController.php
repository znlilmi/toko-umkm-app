<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin platform performance dashboard with cached chart data.
     */
    public function index(): View
    {
        // ── Global KPI (Cached for 10 minutes) ─────────────────────────
        $kpis = Cache::remember('admin_dashboard_kpis', 600, function () {
            $totalRevenue    = Order::where('status', 'completed')->sum('grand_total');
            $totalCommission = round((float) $totalRevenue * 0.05, 2); // 5% platform fee

            return [
                'totalUsers'        => User::count(),
                'totalShops'        => Shop::count(),
                'totalActiveShops'  => Shop::where('is_active', true)->count(),
                'pendingShops'      => Shop::where('status', 'pending')->count(),
                'totalOrders'       => Order::count(),
                'totalRevenue'      => $totalRevenue,
                'totalCommission'   => $totalCommission,
            ];
        });

        $totalUsers        = $kpis['totalUsers'];
        $totalShops        = $kpis['totalShops'];
        $totalActiveShops  = $kpis['totalActiveShops'];
        $pendingShops      = $kpis['pendingShops'];
        $totalOrders       = $kpis['totalOrders'];
        $totalRevenue      = $kpis['totalRevenue'];
        $totalCommission   = $kpis['totalCommission'];

        // ── Recent Data (Not cached to ensure fresh view of latest events) ──
        $recentOrders = Order::with(['customer', 'shop'])
            ->latest()
            ->take(10)
            ->get();

        $recentUsers = User::latest()->take(10)->get();

        // ── Chart Data: Platform Trend (30 days - Cached for 10 mins) ──
        $platformTrendData = $this->getPlatformTrendData();

        // ── Chart Data: Top 10 Merchants by GMV (Cached for 10 mins) ──
        $merchantPerformanceData = $this->getMerchantPerformanceData();

        // ── Chart Data: Order Status Distribution (Cached for 10 mins) ──
        $orderStatusData = $this->getOrderStatusData();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalShops',
            'totalActiveShops',
            'pendingShops',
            'totalOrders',
            'totalRevenue',
            'totalCommission',
            'recentOrders',
            'recentUsers',
            'platformTrendData',
            'merchantPerformanceData',
            'orderStatusData',
        ));
    }

    /**
     * AJAX endpoint: Return fresh platform KPI numbers (JSON, from cache).
     */
    public function kpiData(): JsonResponse
    {
        $kpis = Cache::remember('admin_dashboard_kpis', 600, function () {
            $totalRevenue    = Order::where('status', 'completed')->sum('grand_total');
            $totalCommission = round((float) $totalRevenue * 0.05, 2);

            return [
                'totalUsers'        => User::count(),
                'totalShops'        => Shop::count(),
                'totalActiveShops'  => Shop::where('is_active', true)->count(),
                'pendingShops'      => Shop::where('status', 'pending')->count(),
                'totalOrders'       => Order::count(),
                'totalRevenue'      => $totalRevenue,
                'totalCommission'   => $totalCommission,
            ];
        });

        return response()->json([
            'total_users'       => $kpis['totalUsers'],
            'total_shops'       => $kpis['totalShops'],
            'total_active_shops'=> $kpis['totalActiveShops'],
            'pending_shops'     => $kpis['pendingShops'],
            'total_orders'      => $kpis['totalOrders'],
            'total_revenue'     => (float) $kpis['totalRevenue'],
            'total_commission'  => $kpis['totalCommission'],
            'refreshed_at'      => Carbon::now()->format('H:i:s'),
        ]);
    }

    /**
     * Clear all cached admin dashboard metrics and redirect back.
     */
    public function refresh(): RedirectResponse
    {
        // Restrict to admin only
        abort_if(auth()->user()->role !== 'admin', 403, 'Akses ditolak.');

        Cache::forget('admin_dashboard_kpis');
        Cache::forget('admin_dashboard_trend_data');
        Cache::forget('admin_dashboard_merchant_performance');
        Cache::forget('admin_dashboard_order_status');

        return redirect()->route('admin.dashboard')
            ->with('success', 'Data dashboard berhasil diperbarui!');
    }

    /**
     * Platform-wide GMV trend for the past 30 days (Cached for 10 mins).
     *
     * @return array{labels: string[], gmv: float[], count: int[]}
     */
    private function getPlatformTrendData(): array
    {
        return Cache::remember('admin_dashboard_trend_data', 600, function () {
            $rows = Order::where('status', 'completed')
                ->where('created_at', '>=', Carbon::now()->subDays(29)->startOfDay())
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(grand_total) as gmv'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');

            $labels   = [];
            $gmvArr   = [];
            $countArr = [];

            for ($i = 29; $i >= 0; $i--) {
                $day       = Carbon::now()->subDays($i)->format('Y-m-d');
                $labels[]  = Carbon::parse($day)->format('d M');
                $gmvArr[]  = isset($rows[$day]) ? (float) $rows[$day]->gmv   : 0;
                $countArr[]= isset($rows[$day]) ? (int)   $rows[$day]->count : 0;
            }

            return compact('labels', 'gmvArr', 'countArr');
        });
    }

    /**
     * Top 10 merchants ranked by completed-order GMV (Cached for 10 mins).
     *
     * @return array{labels: string[], gmv: float[]}
     */
    private function getMerchantPerformanceData(): array
    {
        return Cache::remember('admin_dashboard_merchant_performance', 600, function () {
            $rows = DB::table('orders')
                ->join('shops', 'orders.shop_id', '=', 'shops.id')
                ->where('orders.status', 'completed')
                ->select('shops.name as shop_name', DB::raw('SUM(orders.grand_total) as gmv'))
                ->groupBy('shops.id', 'shops.name')
                ->orderByDesc('gmv')
                ->limit(10)
                ->get();

            $labels = $rows->pluck('shop_name')->toArray();
            $gmv    = $rows->pluck('gmv')->map(fn ($v) => (float) $v)->toArray();

            return compact('labels', 'gmv');
        });
    }

    /**
     * Distribution of orders by status (Cached for 10 mins).
     *
     * @return array{labels: string[], values: int[]}
     */
    private function getOrderStatusData(): array
    {
        return Cache::remember('admin_dashboard_order_status', 600, function () {
            $rows = Order::select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get();

            $labelMap = [
                'pending_payment'  => 'Menunggu Pembayaran',
                'verifying_payment'=> 'Verifikasi Pembayaran',
                'processing'       => 'Diproses',
                'shipped'          => 'Dikirim',
                'completed'        => 'Selesai',
                'cancelled'        => 'Dibatalkan',
            ];

            $labels = $rows->map(fn ($r) => $labelMap[$r->status] ?? ucfirst($r->status))->toArray();
            $values = $rows->pluck('count')->map(fn ($v) => (int) $v)->toArray();

            return compact('labels', 'values');
        });
    }
}
