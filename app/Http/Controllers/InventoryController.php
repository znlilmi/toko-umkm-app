<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMutation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    private function getShop()
    {
        $shop = auth()->user()->shop;
        abort_if(! $shop, 404, 'Toko tidak ditemukan.');

        return $shop;
    }

    /**
     * Display a list of all products with their current stock levels.
     */
    public function index(): View
    {
        $shop     = $this->getShop();
        $products = $shop->products()->withTrashed()->orderBy('stock')->paginate(30);

        return view('merchant.inventory.index', compact('products'));
    }

    /**
     * Show the stock mutation (ledger) history for a specific product.
     */
    public function show(Product $product): View
    {
        $shop = $this->getShop();
        abort_if($product->shop_id !== $shop->id, 403);

        $mutations = $product->mutations()->latest()->paginate(30);

        return view('merchant.inventory.show', compact('product', 'mutations'));
    }

    /**
     * Manually adjust stock (IN or OUT) for a product.
     */
    public function adjust(Request $request, Product $product, \App\Services\InventoryService $inventoryService): RedirectResponse
    {
        $shop = $this->getShop();
        abort_if($product->shop_id !== $shop->id, 403);

        $data = $request->validate([
            'qty'         => ['required', 'integer', 'min:1'],
            'type'        => ['required', 'in:IN,OUT'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $inventoryService->adjustStock($product, $data['qty'], $data['type'], $data['description'] ?? null);

        return redirect()->route('merchant.inventory.show', $product)
            ->with('success', 'Stok berhasil disesuaikan.');
    }
}
