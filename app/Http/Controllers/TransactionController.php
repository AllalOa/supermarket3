<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // Show the sale page with active bill
    public function showSalePage()
    {
        $billId = session('activeBillId');
        if (!$billId) {
            return redirect()->route('cashier.dashboard')->with('error', 'Start a new sale first.');
        }

        
        $bill = Bill::find($billId);
        return view('cashier.newsale', [
            'billId' => $bill->id,
            'saleDate' => $bill->created_at->format('Y-m-d'),
            'saleTime' => $bill->created_at->format('H:i:s'),
        ]);
    }

    // Start a new sale
    public function startNewSale()
    {
        // Create a new bill for the logged-in cashier
        $bill = Bill::create([
            'cashier_id' => auth()->id(),
            'total' => 0
        ]);

        // Store bill ID in session
        session(['activeBillId' => $bill->id]);

        return redirect()->route('cashier.sale');
    }

    // Add a product to the bill
    public function addProductToBill(Request $request)
    {
        $request->validate([
            'barcode' => 'required',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $product = Product::where('barcode', $request->barcode)->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $billId = session('activeBillId');
        if (!$billId) {
            return response()->json(['error' => 'No active bill found'], 400);
        }

        $quantity = $request->quantity ?? 1;
       
        $total = $product->unit_price * $quantity;

        // Create transaction
        $transaction = Transaction::create([
            'bill_id' => $billId,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->unit_price,

            'total' => $total
        ]);

        // Update bill total
        $bill = Bill::find($billId);
        $bill->total += $total;
        $bill->save();

        return response()->json([
            'transaction' => [
                'name' => $product->name,
                'quantity' => $transaction->quantity,
                'price' => $transaction->price,
                
                'total' => $transaction->total
            ],
            'bill' => $bill
        ]);
    }

    // End the transaction (Clear session and redirect)
    public function endTransaction()
    {
        session()->forget('activeBillId');

        return redirect()->route('cashier.dashboard')->with('success', 'Transaction completed.');
 
 
    }



    public function getBillTransactions($billId)
    {
        try {
            \Log::info('Fetching bill transactions for bill ID: ' . $billId);
            
            $bill = Bill::with(['transactions.product', 'cashier'])
                ->where('id', $billId)
                ->first();

            if (!$bill) {
                \Log::error('Bill not found: ' . $billId);
                return response()->json([
                    'error' => 'Bill not found'
                ], 404);
            }

            \Log::info('Found bill: ', ['bill_id' => $bill->id, 'total' => $bill->total]);

            $transactions = $bill->transactions->map(function ($transaction) {
                if (!$transaction->product) {
                    \Log::warning('Product not found for transaction: ' . $transaction->id);
                    return null;
                }
                
                return [
                    'id' => $transaction->id,
                    'product_id' => $transaction->product_id,
                    'name' => $transaction->product->name,
                    'quantity' => $transaction->quantity,
                    'unit_price' => $transaction->price,
                    'total' => $transaction->total
                ];
            })->filter();

            \Log::info('Processed transactions: ' . $transactions->count());

            $response = [
                'bill' => [
                    'id' => $bill->id,
                    'total' => $bill->total,
                    'created_at' => $bill->created_at,
                    'cashier_name' => $bill->cashier ? $bill->cashier->name : 'Unknown'
                ],
                'transactions' => $transactions
            ];

            \Log::info('Returning response for bill', ['bill_id' => $bill->id]);
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            \Log::error('Error in getBillTransactions: ' . $e->getMessage(), [
                'bill_id' => $billId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error loading bill: ' . $e->getMessage()
            ], 500);
        }
    }



public function dashboard()
{
    $cashierId = auth()->user()->id;  // Assuming you're using the logged-in user's ID
    
    // Get the user with their foyer information
    $user = auth()->user()->load('foyer');
    
    // Get recent bills
    $recentBills = Bill::with('transactions.product')
        ->where('cashier_id', $cashierId)
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();
    
    // Calculate total number of bills today
    $totalBillsToday = Bill::where('cashier_id', $cashierId)
        ->whereDate('created_at', now())
        ->count();

    // Calculate total items sold today
    $totalItemsSold = Transaction::whereHas('bill', function ($query) use ($cashierId) {
        $query->where('cashier_id', $cashierId)
            ->whereDate('created_at', now());
    })->sum('quantity');

    // Calculate total sales amount for today
    $somme = Transaction::whereHas('bill', function ($query) use ($cashierId) {
        $query->where('cashier_id', $cashierId)
            ->whereDate('created_at', now());
    })->sum('total');

    return view('cashier.dashboard', compact('recentBills', 'totalItemsSold', 'somme', 'totalBillsToday', 'user'));
}

    public function getAllTransactions(Request $request)
    {
        $cashierId = auth()->user()->id;
        $query = Bill::with(['transactions.product', 'cashier'])
            ->where('cashier_id', $cashierId);

        // Search by bill number
        if ($request->has('search') && !empty($request->search)) {
            $query->where('id', 'like', '%' . $request->search . '%');
        }

        // Filter by date
        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('created_at', $request->date);
        }

        // Order by latest first
        $query->orderBy('created_at', 'desc');

        // Get paginated results
        $bills = $query->paginate(20)->withQueryString();
        
        return view('cashier.transactions', [
            'bills' => $bills,
            'user' => auth()->user()->load('foyer'),
            'search' => $request->search,
            'date' => $request->date
        ]);
    }

    public function processSale(Request $request)
    {
        try {
            // Validate request data
            $request->validate([
                'bill_id' => 'required|exists:bills,id',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit_price' => 'required|numeric|min:0',
                'items.*.total' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'payment_amount' => 'required|numeric|min:0',
                'change_amount' => 'required|numeric|min:0',
            ]);

            $bill = Bill::find($request->bill_id);
            
            if (!$bill) {
                return response()->json(['success' => false, 'message' => 'Bill not found'], 404);
            }

            // Start transaction
            DB::beginTransaction();

            try {
                // Update bill with payment details
                $bill->update([
                    'total' => $request->total,
                    'payment_amount' => $request->payment_amount,
                    'change_amount' => $request->change_amount
                ]);

                // Process transactions
                foreach ($request->items as $item) {
                    Transaction::create([
                        'bill_id' => $bill->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['unit_price'],
                        'total' => $item['total']
                    ]);
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Sale processed successfully']);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing sale: ' . $e->getMessage()
            ], 500);
        }
    }

}
