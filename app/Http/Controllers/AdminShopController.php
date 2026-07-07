<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminShopController extends Controller
{
    /**
     * Display a list of all shops with their status for moderation.
     */
    public function index(Request $request): View
    {
        $status = $request->get('status', 'pending');

        $shops = Shop::with('user')
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(30);

        return view('admin.shops.index', compact('shops', 'status'));
    }

    /**
     * Display the details of a specific shop.
     */
    public function show(Shop $shop): View
    {
        $shop->load('user');

        return view('admin.shops.show', compact('shop'));
    }

    /**
     * Approve and activate a pending shop.
     */
    public function verify(Shop $shop): RedirectResponse
    {
        abort_if($shop->status !== 'pending', 422, 'Toko tidak dalam status pending.');

        $shop->update([
            'status'    => 'active',
            'is_active' => true,
        ]);

        return redirect()->route('admin.shops.index')
            ->with('success', "Toko \"{$shop->name}\" berhasil diverifikasi dan diaktifkan.");
    }

    /**
     * Reject a pending shop application.
     */
    public function reject(Shop $shop): RedirectResponse
    {
        abort_if($shop->status !== 'pending', 422, 'Toko tidak dalam status pending.');

        $shop->update(['status' => 'rejected']);

        return redirect()->route('admin.shops.index')
            ->with('success', "Toko \"{$shop->name}\" telah ditolak.");
    }

    /**
     * Suspend an active shop.
     */
    public function suspend(Shop $shop): RedirectResponse
    {
        $shop->update([
            'status'    => 'suspended',
            'is_active' => false,
        ]);

        return redirect()->route('admin.shops.index')
            ->with('success', "Toko \"{$shop->name}\" telah disuspend.");
    }
}
