<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /**
     * Store a new review for a completed order item.
     */
    public function store(StoreReviewRequest $request, \App\Services\ReviewService $reviewService): RedirectResponse
    {
        $data      = $request->validated();
        $orderItem = OrderItem::with('order', 'product')->findOrFail($data['order_item_id']);

        // Ensure the review belongs to the authenticated customer's order.
        abort_if($orderItem->order->customer_id !== auth()->id(), 403);
        abort_if($orderItem->order->status !== 'completed', 403, 'Pesanan belum selesai.');

        $reviewService->createReview($data, $orderItem);

        return redirect()->route('orders.show', $orderItem->order_id)
            ->with('success', 'Ulasan berhasil dikirim. Terima kasih!');
    }
}
