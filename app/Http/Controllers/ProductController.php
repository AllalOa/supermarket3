<?php


namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    // Show Add Product Form
    public function create()
    {
        return view('magazinier.addProduct');
    }

    // Store New Product
    public function store(Request $request)
    {
        try {
            $request->validate([
                'barcode' => 'required|unique:products',
                'name' => 'required|string|max:255',
                'category' => 'required|string',
                'quantity' => 'required|integer|min:0',
                'price' => 'required|numeric|min:0',
                'unit_price' => 'required|numeric|min:0',
                'product_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $productPicturePath = null;

            if ($request->hasFile('product_picture')) {
                $productPicturePath = $request->file('product_picture')->store('product_images', 'public');
            }

            $product = Product::create([
                'barcode' => $request->barcode,
                'product_picture' => $productPicturePath,
                'name' => $request->name,
                'category' => $request->category,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'unit_price' => $request->unit_price,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product added successfully!',
                    'product' => $product
                ]);
            }

            return redirect()->back()->with('success', 'Product added successfully!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function inventory()
    {
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
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'quantity' => 'required|integer|min:0',
                'price' => 'required|numeric|min:0',
                'unit_price' => 'required|numeric|min:0',
                'product_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // Find the product
            $product = Product::findOrFail($id);
            
            // Update basic info
            $product->name = $request->name;
            $product->category = $request->category;
            $product->quantity = $request->quantity;
            $product->price = $request->price;
            $product->unit_price = $request->unit_price;

            // Handle image upload if provided
            if ($request->hasFile('product_picture')) {
                $productPicturePath = $request->file('product_picture')->store('product_images', 'public');
                $product->product_picture = $productPicturePath;
            }

            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'product' => $product
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Product update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete(); // Delete the product

        return redirect()->route('magazinier.inventory')->with('success', 'Product deleted successfully');
    }



    public function getProductPrice($productId)
    {
        // Find the product by ID
        $product = Product::find($productId);
    
        if ($product) {
            return response()->json(['price' => $product->price, 'name' => $product->name]); // Return price and name
        } else {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }
    }

    public function showDemandForm()
    {
        $products = Product::all();  // Fetch all products from the product table
        return view('cashier.demand2', compact('products'));
    }
}
