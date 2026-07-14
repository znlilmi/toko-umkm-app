<?php

namespace App\Services;

use App\Models\User;
use App\Models\Cart;
use App\Models\Product;

class CartService
{
    /**
     * Add a product to the cart, or increment quantity if it already exists.
     *
     * @param User $user
     * @param int $productId
     * @param int $qty
     * @return Cart
     */
    public function addToCart(User $user, int $productId, int $qty): Cart
    {
        $product = Product::findOrFail($productId);

        $cartItem = $user->carts()
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('qty', $qty);
        } else {
            $cartItem = $user->carts()->create([
                'product_id' => $product->id,
                'qty'        => $qty,
            ]);
        }

        return $cartItem;
    }
}
