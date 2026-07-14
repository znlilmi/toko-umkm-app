<?php

namespace App\Services;

use App\Models\User;
use App\Models\Shop;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ShopService
{
    /**
     * Register a new shop and change user role to merchant.
     *
     * @param User $user
     * @param array $data
     * @param UploadedFile|null $logo
     * @param UploadedFile|null $banner
     * @return Shop
     */
    public function registerShop(User $user, array $data, ?UploadedFile $logo = null, ?UploadedFile $banner = null): Shop
    {
        if ($logo) {
            $data['logo'] = $logo->store('shops/logos', 'public');
        }
        if ($banner) {
            $data['banner'] = $banner->store('shops/banners', 'public');
        }

        $shop = $user->shop()->create($data);

        // Update user role to merchant once shop is registered.
        $user->update(['role' => 'merchant']);

        return $shop;
    }

    /**
     * Update the merchant's shop profile.
     *
     * @param Shop $shop
     * @param array $data
     * @param UploadedFile|null $logo
     * @param UploadedFile|null $banner
     * @return Shop
     */
    public function updateShop(Shop $shop, array $data, ?UploadedFile $logo = null, ?UploadedFile $banner = null): Shop
    {
        if ($logo) {
            if ($shop->logo) {
                Storage::disk('public')->delete($shop->logo);
            }
            $data['logo'] = $logo->store('shops/logos', 'public');
        }
        if ($banner) {
            if ($shop->banner) {
                Storage::disk('public')->delete($shop->banner);
            }
            $data['banner'] = $banner->store('shops/banners', 'public');
        }

        $shop->update($data);

        return $shop;
    }
}
