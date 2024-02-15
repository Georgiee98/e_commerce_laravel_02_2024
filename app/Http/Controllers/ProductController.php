<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    // Display all products
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function showProductsByCategory($categoryName)
    {
        $category = Category::where('name', $categoryName)->first();
        if (!$category) {
            abort(404, 'Category not found');
        }

        $products = $category->products()->get();
        return view('products.category', compact('products', 'category'));
    }
    public function store(Request $request)
    {
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id; // Assuming you have a select dropdown in your form
        $product->save();

        return back()->with('success', 'Product added successfully!');
    }

    // Show single product details
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}