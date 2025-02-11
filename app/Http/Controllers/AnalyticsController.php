<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Bill;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function analytics(Request $request)
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString()); // Default: Beginning of this month
        $endDate = $request->query('end_date', now()->toDateString()); // Default: Today
    
        // Fetch sales per cashier
        $transactions = DB::table('bills')
            ->join('users', 'bills.cashier_id', '=', 'users.id')
            ->whereBetween('bills.created_at', [$startDate, $endDate])
            ->selectRaw("CONCAT(users.name, ' ') as cashier_name, SUM(bills.total) as total_sales")
            ->groupBy('bills.cashier_id', 'cashier_name')
            ->get();
    
        // Fetch detailed bill information per cashier
        $cashierBills = DB::table('bills')
            ->join('users', 'bills.cashier_id', '=', 'users.id')
            ->whereBetween('bills.created_at', [$startDate, $endDate])
            ->select('users.name as cashier_name', 'bills.total', 'bills.created_at')
            ->orderBy('bills.created_at', 'asc')
            ->get();
    
        return view('supervisor.analytics', compact('transactions', 'cashierBills', 'startDate', 'endDate'));
    }
}
