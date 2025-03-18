<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityHelper;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Notification;
use App\Events\NewOrderNotification;

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



        // Get magazinier's user ID (example: first user)
        $magazinierUserId = 6;

        // Save notification to database
        Notification::create([
            'user_id' => $magazinierUserId,
            'message' => "New order #{$order->id} requires processing",
        ]);

        // Broadcast the event
        event(new NewOrderNotification("New order #{$order->id} placed!", $magazinierUserId));
        ActivityHelper::log("Created a new order", $order);
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
        $orderDetails = OrderDetail::where('order_id', $id)->get();

        foreach ($orderDetails as $detail) {
            $product = Product::findOrFail($detail->product_id);

            if ($product->quantity < $detail->quantity) {
                return redirect()->back()->with('error', 'Stock insuffisant pour : ' . $product->name);
            }
        }

        foreach ($orderDetails as $detail) {
            $product = Product::findOrFail($detail->product_id);
            $product->quantity -= $detail->quantity;
            $product->save();

            $detail->status = 'approved';
            $detail->save();
        }

        $order->status = 'approved';
        $order->save();

        return redirect()->back()->with('success', 'Commande validée avec succès.');
    }



    public function rejectOrder($id)
    {
        $order = Order::findOrFail($id);
        $orderDetails = OrderDetail::where('order_id', $id)->get();

        foreach ($orderDetails as $detail) {
            $detail->status = 'rejected';
            $detail->save();
        }

        $order->status = 'rejected';
        $order->save();

        return redirect()->back()->with('error', 'Commande rejetée.');
    }



    // return cashier orders 
    public function cashierOrders()
    {
        $orders = Order::where('cashier_id', auth()->id())->with('details.product')->orderBy('created_at', 'desc')->get();

        return view('cashier.orders', compact('orders'));
    }
}
