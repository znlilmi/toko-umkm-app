<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shop;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin platform performance dashboard.
     */
    public function index(): View
    {
        $totalUsers        = User::count();
        $totalShops        = Shop::count();
        $totalActiveShops  = Shop::where('is_active', true)->count();
        $pendingShops      = Shop::where('status', 'pending')->count();
        $totalOrders       = Order::count();
        $totalRevenue      = Order::where('status', 'completed')->sum('grand_total');

        $recentOrders = Order::with(['customer', 'shop'])
            ->latest()
            ->take(10)
            ->get();

        $recentUsers = User::latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalShops',
            'totalActiveShops',
            'pendingShops',
            'totalOrders',
            'totalRevenue',
            'recentOrders',
            'recentUsers'
        ));
    }
}
