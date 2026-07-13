<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display the public product catalog with search and category filter.
     */
    public function index(Request $request)
    {
        $products = Product::with(['shop', 'categories'])
            ->where('is_active', true)
            ->whereHas('shop', fn ($q) => $q->where('is_active', true))
            ->filter($request->only(['q', 'category', 'min_price', 'max_price']))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Display a single product detail page.
     */
    public function show(string $slug)
    {
        $product = Product::with(['shop', 'categories', 'reviews'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('products.show', compact('product'));
    }
}
