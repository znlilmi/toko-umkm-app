<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\View\View;

class MerchantReviewController extends Controller
{
    private function getShop()
    {
        $shop = auth()->user()->shop;
        abort_if(! $shop, 404, 'Toko tidak ditemukan.');

        return $shop;
    }

    /**
     * Display a list of reviews for products in the merchant's shop.
     */
    public function index(): View
    {
        $shop = $this->getShop();

        $reviews = Review::whereHas('product', function ($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })
        ->with(['product', 'orderItem.order.customer'])
        ->latest()
        ->paginate(20);

        // Calculate some statistics
        $averageRating = Review::whereHas('product', function ($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })->avg('rating') ?: 0;

        $totalReviews = Review::whereHas('product', function ($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })->count();

        return view('merchant.reviews.index', compact('reviews', 'averageRating', 'totalReviews', 'shop'));
    }
}
