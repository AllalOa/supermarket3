<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityHelper;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use App\Models\Notification;
use App\Events\NewOrderNotification;
use App\Events\OrderStatusUpdated;
use App\Events\LowStockAlert;
use App\Events\ZeroStockAlert;
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
    
        // Get magazinier's user ID
        $magazinierUserId = 6; // You should use a more dynamic way to get this
    
        // Create the notification message
        $notificationMessage = "New order #{$order->id} requires processing";
    
        // Save notification to database
        $notification = Notification::create([
            'user_id' => $magazinierUserId,
            'message' => $notificationMessage,
            'is_read' => false,
        ]);
    
        // Broadcast the event with the notification ID
        event(new NewOrderNotification($notificationMessage,$magazinierUserId,  $notification->id));
        
        // Log activity
        ActivityHelper::log("Created a new order", $order);
        
        // Set success flash message
        session()->flash('success', 'Order passed successfully!');
    
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
        
        // Check stock for each order detail
        foreach ($orderDetails as $detail) {
            $product = Product::findOrFail($detail->product_id);
            if ($product->quantity < $detail->quantity) {
                return redirect()->back()->with('error', 'Stock insuffisant pour : ' . $product->name);
            }
        }
        
        $lowStockProducts = [];
        $zeroStockProducts = [];
        
        // Deduct stock and update order detail statuses
        foreach ($orderDetails as $detail) {
            $product = Product::findOrFail($detail->product_id);
            $product->quantity -= $detail->quantity;
            $product->save();
            
            // Check if product is low in stock or out of stock after this order
            if ($product->quantity == 0) {
                $zeroStockProducts[] = $product;
            } elseif ($product->quantity < 5) {
                $lowStockProducts[] = $product;
            }
            
            $detail->status = 'approved';
            $detail->save();
        }
        
        // Update order status to approved
        $order->status = 'approved';
        $order->save();
        
        // Get the cashier's user ID
        $cashierId = $order->cashier_id;
        
        // Create the notification message for cashier
        $notificationMessage = "Votre commande #{$order->id} a été validée.";
        
        // Save notification to the database for cashier
        $notification = \App\Models\Notification::create([
            'user_id' => $cashierId,
            'message' => $notificationMessage,
            'is_read' => false,
        ]);
        
        // Dispatch the event for cashier notification
        event(new OrderStatusUpdated($order, 'approved', $notificationMessage));
        
        // Get supervisor ID(s)
        $supervisors = \App\Models\User::where('role', 'supervisor')->get();
        
        // Send notifications to supervisors about low stock products
        if (!empty($lowStockProducts)) {
            foreach ($supervisors as $supervisor) {
                foreach ($lowStockProducts as $product) {
                    $lowStockMessage = "Alerte: Le produit '{$product->name}' a un stock faible ({$product->quantity} restants).";
                    
                    // Save notification to database for supervisor
                    \App\Models\Notification::create([
                        'user_id' => $supervisor->id,
                        'message' => $lowStockMessage,
                        'is_read' => false,
                    ]);
                    
                    // Dispatch event for supervisor notification
                    event(new LowStockAlert($product, $lowStockMessage, $supervisor->id));
                }
            }
        }
        
        // Send notifications to supervisors about out of stock products
        if (!empty($zeroStockProducts)) {
            foreach ($supervisors as $supervisor) {
                foreach ($zeroStockProducts as $product) {
                    $zeroStockMessage = "Alerte URGENT: Le produit '{$product->name}' est en rupture de stock!";
                    
                    // Save notification to database for supervisor
                    \App\Models\Notification::create([
                        'user_id' => $supervisor->id,
                        'message' => $zeroStockMessage,
                        'is_read' => false,
                    ]);
                    
                    // Dispatch event for supervisor notification
                    event(new ZeroStockAlert($product, $zeroStockMessage, $supervisor->id));
                }
            }
        }
        
        return redirect()->back()->with('success', 'Commande validée avec succès.');
    }
    
    public function rejectOrder($id)
    {
        $order = Order::findOrFail($id);
        $orderDetails = OrderDetail::where('order_id', $id)->get();
    
        // Update each order detail status to rejected
        foreach ($orderDetails as $detail) {
            $detail->status = 'rejected';
            $detail->save();
        }
    
        // Update order status to rejected
        $order->status = 'rejected';
        $order->save();
    
        // Get the cashier's user ID
        $cashierId = $order->cashier_id;
    
        // Create the notification message
        $notificationMessage = "Votre commande #{$order->id} a été rejetée.";
    
        // Save notification to the database
        $notification = \App\Models\Notification::create([
            'user_id' => $cashierId,
            'message' => $notificationMessage,
            'is_read' => false,
        ]);
    
        // Dispatch the event (using the OrderStatusUpdated event)
        event(new OrderStatusUpdated($order, 'rejected', $notificationMessage));
    
        return redirect()->back()->with('error', 'Commande rejetée.');
    }
    
    



    // return cashier orders 
    public function cashierOrders()
    {
        $orders = Order::where('cashier_id', auth()->id())->with('details.product')->orderBy('created_at', 'desc')->get();

        return view('cashier.orders', compact('orders'));
    }
}
