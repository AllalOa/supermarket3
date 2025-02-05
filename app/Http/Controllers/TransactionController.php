<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Transaction;
use App\Models\Product;

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
    $bill = Bill::with('transactions.product')->find($billId);

    if (!$bill) {
        return response()->json(['error' => 'Bill not found.'], 404);
    }

    return response()->json([
        'bill' => $bill,
        'transactions' => $bill->transactions->map(function ($transaction) {
            return [
                'name' => $transaction->product->name,
                'quantity' => $transaction->quantity,
                'total' => $transaction->total
            ];
        }),
    ]);
}



public function dashboard()
{
    $cashierId = auth()->user()->id;  // Assuming you're using the logged-in user's ID
    $transactions = Transaction::whereHas('bill', function ($query) use ($cashierId) {
        $query->where('cashier_id', $cashierId);
    })
    ->orderBy('created_at', 'desc')
    ->take(10)
    ->get();
    

    return view('cashier.dashboard', compact('transactions'));
}

}
