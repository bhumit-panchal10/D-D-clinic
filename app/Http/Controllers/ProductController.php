<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // Display product list
    public function index(Request $request)
{
    $products = Product::orderBy('created_at', 'desc')->paginate(config('app.per_page')); // Show latest added first
    $product = $request->has('edit') ? Product::find($request->edit) : null;

    return view('product.index', compact('products', 'product'));
}


    // Store new product or update existing one
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|min:2|max:100',
            'quantity' => 'required|integer',
            'product_location' => 'required|string|min:2|max:150',
        ]);

        Product::create($request->all());

        return redirect()->route('product.index')->with('success', 'Product added successfully.');
    }

    // Update product
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|min:2|max:100',
            'quantity' => 'required|integer',
            'product_location' => 'required|string|min:2|max:150',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect()->route('product.index', ['page' => $request->query('page', 1)])->with('success', 'Product updated successfully.');
    }

    // Delete product
    public function destroy(Request $request, $id)
    {
        Product::findOrFail($id)->delete();

        return redirect()->route('product.index', ['page' => $request->query('page', 1)])->with('success', 'Product deleted successfully.');
    }
}
