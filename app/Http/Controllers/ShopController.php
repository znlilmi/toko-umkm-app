<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ShopController extends Controller
{
    /**
     * Show the shop registration form for a customer.
     */
    public function create(): View
    {
        return view('shop.create');
    }

    /**
     * Register a new shop and change user role to merchant.
     */
    public function store(StoreShopRequest $request, \App\Services\ShopService $shopService): RedirectResponse
    {
        $shopService->registerShop(
            auth()->user(),
            $request->validated(),
            $request->file('logo'),
            $request->file('banner')
        );

        return redirect()->route('merchant.dashboard')
            ->with('success', 'Toko berhasil didaftarkan. Menunggu verifikasi admin.');
    }

    /**
     * Show the merchant shop settings/edit form.
     */
    public function edit(): View
    {
        $shop = auth()->user()->shop;
        abort_if(! $shop, 404);

        return view('shop.edit', compact('shop'));
    }

    /**
     * Update the merchant's shop profile.
     */
    public function update(UpdateShopRequest $request, \App\Services\ShopService $shopService): RedirectResponse
    {
        $shop = auth()->user()->shop;
        abort_if(! $shop, 404);

        $shopService->updateShop(
            $shop,
            $request->validated(),
            $request->file('logo'),
            $request->file('banner')
        );

        return redirect()->route('merchant.shop.edit')
            ->with('success', 'Pengaturan toko berhasil diperbarui.');
    }
}
