<?php


namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class ProductController extends Controller {
    
    // Show Add Product Form
    public function create() {
        return view('magazinier.addProduct');
    }

    // Store New Product
    public function store(Request $request) {
        $request->validate([
            'barcode' => 'required|unique:products',
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        Product::create($request->all());

        return redirect()->back()->with('success', 'Product added successfully!');
    }


    public function inventory() {
        $products = Product::all(); // Get all products from the database
        return view('magazinier.inventory', compact('products'));
    }


public function index()
{
    $lowStockProducts = Product::where('quantity', '<', 5)->count();
    $totalProducts = Product::count(); // Get total number of products
    $pendingOrders = Order::where('status', 'pending')->count(); // Count pending orders

    return view('magazinier.dashboard', compact('totalProducts', 'lowStockProducts', 'pendingOrders'));
}

public function edit($id)
{
    $product = Product::findOrFail($id); // Get the product by ID
    return view('magazinier.edit', compact('product')); // Return edit view with product data
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'category' => 'required|string|max:255',
        'quantity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'unit_price' => 'required|numeric|min:0',
    ]);

    // Find the product and update
    $product = Product::findOrFail($id);
    $product->update([
        'name' => $request->name,
        'category' => $request->category,
        'quantity' => $request->quantity,
        'price' => $request->price,
        'unit_price' => $request->unit_price,
    ]);

    return redirect()->route('magazinier.inventory')->with('success', 'Product updated successfully');
}
public function destroy($id)
{
    $product = Product::findOrFail($id);
    $product->delete(); // Delete the product

    return redirect()->route('magazinier.inventory')->with('success', 'Product deleted successfully');
}



public function getProductPrice($productName)
{
    // Find the product by name
    $product = Product::where('name', $productName)->first();

    if ($product) {
        return response()->json(['price' => $product->price]); // Assuming 'price' is a column in the products table
    } else {
        return response()->json(['error' => 'Produit non trouvé'], 404);
    }
}

}
