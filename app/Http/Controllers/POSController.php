<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\NewOrderNotification;
use App\Helpers\ActivityHelper;

class POSController extends Controller
{
    public function index()
    {
        // Generate a new bill ID
        $lastBill = Bill::latest()->first();
        $billId = $lastBill ? $lastBill->id + 1 : 1;
        
        // Get product categories and ensure they're not empty
        $categories = Product::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->get()
            ->filter()  // Remove any empty values
            ->map(function($item) {
                return [
                    'category' => $item->category,
                    'icon' => $this->getCategoryIcon($item->category)
                ];
            });
            
        // If no categories exist, provide default ones
        if ($categories->isEmpty()) {
            $categories = collect([
                ['category' => 'Food', 'icon' => 'fa-utensils'],
                ['category' => 'Beverages', 'icon' => 'fa-wine-glass'],
                ['category' => 'Snacks', 'icon' => 'fa-cookie'],
                ['category' => 'Household', 'icon' => 'fa-home'],
                ['category' => 'Personal Care', 'icon' => 'fa-pump-soap'],
                ['category' => 'Others', 'icon' => 'fa-box']
            ]);
        }
        
        return view('cashier.pos', compact('billId', 'categories'));
    }

    /**
     * Get appropriate icon for category
     */
    private function getCategoryIcon($category)
    {
        $icons = [
            'Food' => 'fa-utensils',
            'Beverages' => 'fa-wine-glass',
            'Snacks' => 'fa-cookie',
            'Household' => 'fa-home',
            'Personal Care' => 'fa-pump-soap',
            'Others' => 'fa-box'
        ];

        return $icons[$category] ?? 'fa-box';
    }
    
    public function getProducts(Request $request)
    {
        $query = Product::query();
        
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
    
        return response()->json($query->limit(50)->get());
    }
    
    public function getProductByBarcode($barcode)
    {
        $product = Product::where('barcode', $barcode)->first();
        
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        
        return response()->json($product);
    }
    
    public function searchProducts(Request $request)
    {
        $term = $request->get('term');
        
        $products = Product::where('name', 'like', "%{$term}%")
            ->orWhere('barcode', 'like', "%{$term}%")
            ->take(10)
            ->get();
            
        return response()->json($products);
    }
    
    public function checkout(Request $request)
    {
        $request->validate([
            'billId' => 'required',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0'
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create bill
            $bill = Bill::create([
                'cashier_id' => Auth::id(),
                'total' => $request->total
            ]);
            
            // Create transactions and update inventory
            foreach ($request->items as $item) {
                // Create transaction
                Transaction::create([
                    'bill_id' => $bill->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['unit_price'],
                    'total' => $item['total']
                ]);
                
                // Update product inventory
                $product = Product::find($item['id']);
                $product->quantity -= $item['quantity'];
                $product->save();
            }

            // Create notification for magazinier
            $magazinier = User::where('role', 'magazinier')->first();
            if ($magazinier) {
                Notification::create([
                    'user_id' => $magazinier->id,
                    'title' => 'New Sale',
                    'message' => "New sale #" . $bill->id . " requires processing",
                    'type' => 'order',
                    'is_read' => false
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully',
                'bill_id' => $bill->id
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error completing sale: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processSale(Request $request)
    {
        $request->validate([
            'billId' => 'required',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0'
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create bill
            $bill = Bill::create([
                'cashier_id' => Auth::id(),
                'total' => $request->total
            ]);
            
            // Create transactions
            foreach ($request->items as $item) {
                // Create transaction for bill
                Transaction::create([
                    'bill_id' => $bill->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['unit_price'],
                    'total' => $item['total']
                ]);
                
                // Update product inventory
                $product = Product::find($item['id']);
                $product->quantity -= $item['quantity'];
                $product->save();
            }
            
            // Log activity
            ActivityHelper::log("Created a new sale", $bill);
            
            DB::commit();
            
            // Get the transactions for the bill
            $transactions = Transaction::where('bill_id', $bill->id)
                ->join('products', 'transactions.product_id', '=', 'products.id')
                ->select('transactions.*', 'products.name as product_name')
                ->get()
                ->map(function($transaction) {
                    return [
                        'product_name' => $transaction->product_name,
                        'quantity' => $transaction->quantity,
                        'price' => $transaction->price,
                        'total' => $transaction->total
                    ];
                });

            // Create notification for magazinier
            $magazinier = User::where('role', 'magazinier')->first();
            if ($magazinier) {
                Notification::create([
                    'user_id' => $magazinier->id,
                    'title' => 'New Sale',
                    'message' => "New sale #" . $bill->id . " requires processing",
                    'type' => 'order',
                    'is_read' => false
                ]);
            }
            
            // Get the cashier's foyer name
            $cashier = Auth::user();
            $foyerName = $cashier->foyer ? $cashier->foyer->name : 'Unknown Foyer';
            
            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully',
                'bill_id' => $bill->id,
                'items' => $transactions,
                'total' => $request->total,
                'foyer_name' => $foyerName
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error completing sale: ' . $e->getMessage()
            ], 500);
        }
    }
}