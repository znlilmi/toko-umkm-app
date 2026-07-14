<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminReviewController extends Controller
{
    /**
     * Display a listing of all reviews.
     */
    public function index(): View
    {
        $reviews = Review::with(['product.shop', 'orderItem.order.customer'])
            ->latest()
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
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
