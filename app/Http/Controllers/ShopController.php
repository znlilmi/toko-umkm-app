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
    public function store(StoreShopRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('shops/logos', 'public');
        }
        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('shops/banners', 'public');
        }

        $shop = auth()->user()->shop()->create($data);

        // Update user role to merchant once shop is submitted.
        auth()->user()->update(['role' => 'merchant']);

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
    public function update(UpdateShopRequest $request): RedirectResponse
    {
        $shop = auth()->user()->shop;
        abort_if(! $shop, 404);

        $data = $request->validated();

        if ($request->hasFile('logo')) {
            Storage::disk('public')->delete($shop->logo);
            $data['logo'] = $request->file('logo')->store('shops/logos', 'public');
        }
        if ($request->hasFile('banner')) {
            Storage::disk('public')->delete($shop->banner);
            $data['banner'] = $request->file('banner')->store('shops/banners', 'public');
        }

        $shop->update($data);

        return redirect()->route('merchant.shop.edit')
            ->with('success', 'Pengaturan toko berhasil diperbarui.');
    }
}
