<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class MerchantDashboardController extends Controller
{
    /**
     * Display the merchant's financial dashboard with recent orders and sales summary.
     */
    public function index(): View
    {
        $shop = auth()->user()->shop;
        abort_if(! $shop, 404);

        $recentOrders = $shop->orders()
            ->with(['items.product', 'customer', 'payment'])
            ->latest()
            ->take(10)
            ->get();

        $totalRevenue = $shop->orders()
            ->where('status', 'completed')
            ->sum('grand_total');

        $totalOrders = $shop->orders()->count();

        $lowStockProducts = $shop->products()
            ->where('is_active', true)
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->take(10)
            ->get();

        return view('merchant.dashboard', compact(
            'shop',
            'recentOrders',
            'totalRevenue',
            'totalOrders',
            'lowStockProducts'
        ));
    }
}
