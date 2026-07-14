<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminReviewController extends Controller
{
    /**
     * Display a listing of all reviews and ulasan analysis.
     */
    public function index(\Illuminate\Http\Request $request): View
    {
        // 1. Get rating distribution counts for all reviews
        $ratingCounts = Review::selectRaw('rating, count(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = $ratingCounts[$i] ?? 0;
        }

        // 2. Average rating per product with review counts
        $productsRating = \App\Models\Product::with('shop')
            ->whereHas('reviews')
            ->withCount('reviews')
            ->orderByDesc('rating')
            ->paginate(5, ['*'], 'products_page');

        // 3. List of recent reviews for moderation
        $reviews = Review::with(['product.shop', 'orderItem.order.customer'])
            ->latest()
            ->paginate(10, ['*'], 'reviews_page');

        return view('admin.reviews.index', compact('reviews', 'productsRating', 'distribution'));
    }

    /**
     * Remove the specified review from storage and recalculate product average rating.
     */
    public function destroy(Review $review, \App\Services\ReviewService $reviewService): RedirectResponse
    {
        $reviewService->deleteReview($review);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Ulasan berhasil dihapus dan rating produk diperbarui.');
    }
}
