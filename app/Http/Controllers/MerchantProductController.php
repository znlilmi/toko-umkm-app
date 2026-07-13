<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MerchantProductController extends Controller
{
    private function getShop()
    {
        $shop = auth()->user()->shop;
        abort_if(! $shop || ! $shop->is_active, 403, 'Toko tidak aktif atau belum terdaftar.');

        return $shop;
    }

    /**
     * Display a list of the merchant's products.
     */
    public function index(): View
    {
        $shop     = $this->getShop();
        $products = $shop->products()->with('categories')->latest()->paginate(20);

        return view('merchant.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $this->getShop();
        $categories = Category::all();

        return view('merchant.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $shop = $this->getShop();
        $data = $request->validated();

        $product = $shop->products()->create(\Illuminate\Support\Arr::except($data, ['category_ids']));
        $product->categories()->sync($data['category_ids']);

        return redirect()->route('merchant.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $shop = $this->getShop();
        abort_if($product->shop_id !== $shop->id, 403);

        $categories = Category::all();

        return view('merchant.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $shop = $this->getShop();
        abort_if($product->shop_id !== $shop->id, 403);

        $data = $request->validated();

        $product->update(\Illuminate\Support\Arr::except($data, ['category_ids']));

        if (isset($data['category_ids'])) {
            $product->categories()->sync($data['category_ids']);
        }

        return redirect()->route('merchant.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Soft-delete the specified product.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $shop = $this->getShop();
        abort_if($product->shop_id !== $shop->id, 403);

        $product->delete();

        return redirect()->route('merchant.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
