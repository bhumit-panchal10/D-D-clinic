<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\Vendor;
use Illuminate\Http\Request;

class EmployeeProductPurchaseController extends Controller
{
    // List Product Purchases
    public function index(Request $request)
{
    $query = ProductPurchase::with(['product', 'vendor'])->orderBy('created_at', 'desc'); // Show latest first

    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('product', function($q) use ($search) {
            $q->where('product_name', 'LIKE', "%$search%");
        })->orWhereHas('vendor', function($q) use ($search) {
            $q->where('company_name', 'LIKE', "%$search%");
        });
    }

    $purchases = $query->paginate(config('app.per_page'));
    return view('employee.product_purchase.index', compact('purchases'));
}

    // Show Create Form
    public function create()
    {
        $products = Product::orderBy('product_name', 'asc')->get();
        $vendors = Vendor::orderBy('company_name', 'asc')->get();
        return view('employee.product_purchase.create', compact('products', 'vendors'));
    }

    // Store New Purchase Record
    public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'vendor_id' => 'required|exists:vendors,id',
        'quantity' => 'required|integer|min:1|max:9999',
        'rate' => 'required|numeric|min:0.01|max:99999.99',
        'received_date' => 'required|date|before_or_equal:today',
    ]);

    $amount = $request->quantity * $request->rate;

    // Create Product Purchase Entry
    $purchase = ProductPurchase::create([
        'product_id' => $request->product_id,
        'vendor_id' => $request->vendor_id,
        'quantity' => $request->quantity,
        'rate' => $request->rate,
        'amount' => $amount,
        'received_date' => $request->received_date,
    ]);

    // Update Product Quantity in `products` Table
    $product = Product::find($request->product_id);
    $product->quantity += $request->quantity;
    $product->save();

    return redirect()->route('employee.product_purchase.index')->with('success', 'Product purchase added and quantity updated successfully.');
}


    // Delete Purchase Record
    public function destroy(Request $request, $id)
{
    $purchase = ProductPurchase::findOrFail($id);

    // Reduce Product Quantity in `products` Table
    $product = Product::find($purchase->product_id);
    if ($product) {
        $product->quantity -= $purchase->quantity;
        $product->quantity = max($product->quantity, 0); // Prevent negative values
        $product->save();
    }

    $purchase->delete();

    return redirect()->route('employee.product_purchase.index', $request->query())->with('success', 'Product purchase deleted and quantity updated successfully.');
}

    // AJAX Request for Last 4 Purchases
    public function getLastPurchases(Request $request)
    {
    $productId = $request->product_id;
    $purchases = ProductPurchase::with(['product', 'vendor'])
            ->where('product_id', $productId)
            ->orderBy('received_date', 'desc')
            ->limit(4)
            ->get();
        return response()->json($purchases);
    }
}
