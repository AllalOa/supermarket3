<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        // Validate the request
        $request->validate([
            'products_json' => 'required|string',
        ]);

        $products = json_decode($request->products_json, true);

        if (!$products || count($products) == 0) {
            return response()->json(['error' => 'Aucun produit sélectionné'], 400);
        }

        // Create a new order
        $order = Order::create([
            'cashier_id' => auth()->id(), // Assuming the cashier is logged in
            'status' => 'pending',
        ]);

        // Loop through each product and store in OrderDetails
        foreach ($products as $product) {
            // Find the product by name
            $productData = Product::where('name', $product['name'])->first();

            if (!$productData) {
                return response()->json(['error' => 'Produit introuvable : ' . $product['name']], 400);
            }

            // Store order details
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $productData->id,
                'quantity' => $product['quantity'],
                'status' => 'pending',
            ]);
        }

        session()->flash('success', 'Order passed succcesefuly !');

        // Redirect to the cashier dashboard with the success message
        return redirect()->route('cashier.dashboard');
    }
    public function showPendingOrders()
    {
        $orders = Order::where('status', 'pending')->with(['details.product', 'cashier'])->get();
    
        return view('magazinier.orders', compact('orders'));
    }
    
    public function validateOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'approved';
        $order->save();
    
        return redirect()->back()->with('success', 'Commande validée avec succès.');
    }
    
    public function rejectOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'rejected';
        $order->save();
    
        return redirect()->back()->with('error', 'Commande rejetée.');
    }
    

}
