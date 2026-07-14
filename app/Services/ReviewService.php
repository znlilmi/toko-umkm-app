<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    /**
     * Create a new review for a completed order item and update product average rating.
     *
     * @param array $data
     * @param OrderItem $orderItem
     * @return Review
     */
    public function createReview(array $data, OrderItem $orderItem): Review
    {
        return DB::transaction(function () use ($data, $orderItem) {
            $review = $orderItem->review()->create([
                'product_id'    => $orderItem->product_id,
                'order_item_id' => $orderItem->id,
                'rating'        => $data['rating'],
                'comment'       => $data['comment'] ?? null,
            ]);

            // Recalculate and update the product average rating.
            $product   = $orderItem->product;
            $avgRating = $product->reviews()->avg('rating') ?: 0.00;
            $product->update(['rating' => round($avgRating, 2)]);

            return $review;
        });
    }

    /**
     * Delete a review and update product average rating.
     *
     * @param Review $review
     * @return void
     */
    public function deleteReview(Review $review): void
    {
        DB::transaction(function () use ($review) {
            $product = $review->product;

            $review->delete();

            // Recalculate and update the product average rating.
            $avgRating = $product->reviews()->avg('rating') ?: 0.00;
            $product->update(['rating' => round($avgRating, 2)]);
        });
    }
}
