<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MerchantDashboardController extends Controller
{
    /**
     * Display the merchant's financial dashboard with KPI metrics and chart data.
     */
    public function index(): View
    {
        $shop = auth()->user()->shop;
        abort_if(! $shop, 404);

        // ── Recent Orders ──────────────────────────────────────────────
        $recentOrders = $shop->orders()
            ->with(['items.product', 'customer', 'payment'])
            ->latest()
            ->take(10)
            ->get();

        // ── KPI Metrics ────────────────────────────────────────────────
        $totalRevenue = $shop->orders()
            ->where('status', 'completed')
            ->sum('grand_total');

        $successfulOrders = $shop->orders()
            ->where('status', 'completed')
            ->count();

        $totalOrders = $shop->orders()->count();

        $pendingOrders = $shop->orders()
            ->whereIn('status', ['pending_payment', 'verifying_payment'])
            ->count();

        $processingOrders = $shop->orders()
            ->whereIn('status', ['processing', 'shipped'])
            ->count();

        $averageOrderValue = $successfulOrders > 0
            ? round($totalRevenue / $successfulOrders, 0)
            : 0;

        // ── Low Stock Products ─────────────────────────────────────────
        $lowStockProducts = $shop->products()
            ->where('is_active', true)
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->take(10)
            ->get();

        // ── Chart Data: Sales Trend (30 days) ─────────────────────────
        $salesTrendData = $this->getSalesTrendData($shop->id);

        // ── Chart Data: Category Distribution ─────────────────────────
        $categoryDistributionData = $this->getCategoryDistributionData($shop->id);

        return view('merchant.dashboard', compact(
            'shop',
            'recentOrders',
            'totalRevenue',
            'totalOrders',
            'successfulOrders',
            'pendingOrders',
            'processingOrders',
            'averageOrderValue',
            'lowStockProducts',
            'salesTrendData',
            'categoryDistributionData',
        ));
    }

    /**
     * AJAX endpoint: Return fresh KPI numbers for auto-refresh (JSON).
     */
    public function kpiData(): JsonResponse
    {
        $shop = auth()->user()->shop;
        abort_if(! $shop, 404);

        $totalRevenue = $shop->orders()
            ->where('status', 'completed')
            ->sum('grand_total');

        $successfulOrders = $shop->orders()
            ->where('status', 'completed')
            ->count();

        $pendingOrders = $shop->orders()
            ->whereIn('status', ['pending_payment', 'verifying_payment'])
            ->count();

        $processingOrders = $shop->orders()
            ->whereIn('status', ['processing', 'shipped'])
            ->count();

        $averageOrderValue = $successfulOrders > 0
            ? round((float) $totalRevenue / $successfulOrders, 0)
            : 0;

        return response()->json([
            'total_revenue'      => (float) $totalRevenue,
            'successful_orders'  => $successfulOrders,
            'average_order_value'=> $averageOrderValue,
            'shop_balance'       => (float) $shop->balance,
            'pending_orders'     => $pendingOrders,
            'processing_orders'  => $processingOrders,
            'refreshed_at'       => Carbon::now()->format('H:i:s'),
        ]);
    }

    /**
     * Build sales trend data for the past 30 days for a given shop.
     *
     * @return array{labels: string[], gmv: float[], count: int[]}
     */
    private function getSalesTrendData(int $shopId): array
    {
        $rows = Order::where('shop_id', $shopId)
            ->where('status', 'completed')
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

        $labels = [];
        $gmvArr  = [];
        $countArr = [];

        for ($i = 29; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[]  = Carbon::parse($day)->format('d M');
            $gmvArr[]  = isset($rows[$day]) ? (float) $rows[$day]->gmv   : 0;
            $countArr[]= isset($rows[$day]) ? (int)   $rows[$day]->count : 0;
        }

        return compact('labels', 'gmvArr', 'countArr');
    }

    /**
     * Build category distribution data (by qty sold) for a given shop.
     *
     * @return array{labels: string[], values: int[]}
     */
    private function getCategoryDistributionData(int $shopId): array
    {
        $rows = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('category_product', 'products.id', '=', 'category_product.product_id')
            ->join('categories', 'category_product.category_id', '=', 'categories.id')
            ->where('orders.shop_id', $shopId)
            ->where('orders.status', 'completed')
            ->whereNull('categories.parent_id')   // only top-level categories
            ->select('categories.name as category', DB::raw('SUM(order_items.qty) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->get();

        $labels = $rows->pluck('category')->toArray();
        $values = $rows->pluck('total')->map(fn ($v) => (int) $v)->toArray();

        return compact('labels', 'values');
    }
}
