<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Display the authenticated user's wishlist.
     */
    public function index(): View
    {
        $wishlistItems = auth()->user()
            ->wishlists()
            ->with('product.shop')
            ->get();

        return view('wishlist.index', compact('wishlistItems'));
    }

    /**
     * Toggle: add to wishlist, or remove if already present.
     */
    public function store(int $productId): RedirectResponse
    {
        $product  = Product::findOrFail($productId);
        $existing = auth()->user()->wishlists()
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Produk dihapus dari wishlist.';
        } else {
            auth()->user()->wishlists()->create(['product_id' => $product->id]);
            $message = 'Produk ditambahkan ke wishlist.';
        }

        return back()->with('success', $message);
    }

    /**
     * Remove the specified product from the wishlist.
     */
    public function destroy(Wishlist $wishlist): RedirectResponse
    {
        $this->authorize('delete', $wishlist);

        $wishlist->delete();

        return redirect()->route('wishlist.index')
            ->with('success', 'Produk dihapus dari wishlist.');
    }
}
