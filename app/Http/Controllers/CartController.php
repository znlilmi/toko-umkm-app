<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the authenticated user's shopping cart.
     */
    public function index(): View
    {
        $cartItems = auth()->user()
            ->carts()
            ->with('product.shop')
            ->get();

        return view('cart.index', compact('cartItems'));
    }

    /**
     * Add a product to the cart, or increment qty if it already exists.
     */
    public function store(StoreCartRequest $request, \App\Services\CartService $cartService): RedirectResponse
    {
        $data = $request->validated();
        $cartService->addToCart(auth()->user(), $data['product_id'], $data['qty']);

        return redirect()->route('cart.index')
            ->with('success', 'Produk ditambahkan ke keranjang.');
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(UpdateCartRequest $request, Cart $cart): RedirectResponse
    {
        $this->authorize('update', $cart);

        $cart->update($request->validated());

        return redirect()->route('cart.index')
            ->with('success', 'Keranjang diperbarui.');
    }

    /**
     * Remove a product from the cart.
     */
    public function destroy(Cart $cart): RedirectResponse
    {
        $this->authorize('delete', $cart);

        $cart->delete();

        return redirect()->route('cart.index')
            ->with('success', 'Item dihapus dari keranjang.');
    }
}
