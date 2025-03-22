<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index()
    {
        // Generate a new bill ID
        $lastBill = Bill::latest()->first();
        $billId = $lastBill ? $lastBill->id + 1 : 0;
        
        // Get product categories
        $categories = Product::select('category')->distinct()->get();
        
        return view('cashier.pos', compact('billId', 'categories'));
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
    
    public function getProductByBarcode(Request $request)
    {
        $barcode = $request->barcode;
        $product = Product::where('barcode', $barcode)->first();
        
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        
        return response()->json($product);
    }
    
    public function processSale(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'bill_id' => 'required',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0', // Changed from price to unit_price
            'items.*.total' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,amount',
            'total' => 'required|numeric|min:0',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create bill
            $bill = Bill::create([
                'cashier_id' => Auth::id(),
                'total' => $request->total
            ]);
            
            // Create transactions for each item
            foreach ($request->items as $item) {
                Transaction::create([
                    'bill_id' => $bill->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['unit_price'], // Changed from price to unit_price
                    'total' => $item['total']
                ]);
                
                // Update product inventory
                $product = Product::find($item['product_id']);
                $product->quantity -= $item['quantity'];
                $product->save();
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
                'message' => 'Error processing sale: ' . $e->getMessage()
            ], 500);
        }
    }
}