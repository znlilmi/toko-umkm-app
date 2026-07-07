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
        $query = Product::with(['shop', 'categories'])
            ->where('is_active', true)
            ->whereHas('shop', fn ($q) => $q->where('is_active', true));

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', fn ($q) => $q->where('slug', $request->category));
        }

        $products   = $query->latest()->paginate(20)->withQueryString();
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
