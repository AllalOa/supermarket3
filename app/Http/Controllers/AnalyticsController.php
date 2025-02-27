<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Bill;
use App\Models\Product;
use App\Models\Order;
    use App\Models\Activity;

use Carbon\Carbon;
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




        $productSales = Product::leftJoin('transactions', 'products.id', '=', 'transactions.product_id')
            ->select(
                'products.id',
                'products.name as product_name', // ✅ Alias name correctly
                'products.product_picture', // ✅ Include product picture
                'products.price',
                DB::raw('COALESCE(SUM(transactions.quantity), 0) as total_sales'),
                DB::raw('COALESCE(SUM(transactions.quantity * products.price), 0) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.product_picture', 'products.price')
            ->orderByDesc('total_sales') // Sort by highest sales
            ->get();
            $orderStats = DB::table('orders')
            ->join('users', 'orders.cashier_id', '=', 'users.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'users.name as cashier_name',
                DB::raw('COUNT(CASE WHEN orders.status = "pending" THEN 1 END) as pending_orders'),
                DB::raw('COUNT(CASE WHEN orders.status = "approved" THEN 1 END) as approved_orders'),
                DB::raw('COUNT(CASE WHEN orders.status = "rejected" THEN 1 END) as rejected_orders')
            )
            ->groupBy('users.id', 'users.name')
            ->get();



            
                $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
                $endDate = $request->query('end_date', now()->toDateString());
            
                // Get cashier daily orders data
                $dailyOrders = DB::table('orders')
                    ->select(
                        'users.name as cashier_name',
                        DB::raw('DATE(orders.created_at) as order_date'),
                        DB::raw('SUM(order_details.quantity * products.price) as daily_total')
                    )
                    ->join('users', 'orders.cashier_id', '=', 'users.id')
                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->join('products', 'order_details.product_id', '=', 'products.id')
                    ->whereBetween('orders.created_at', [$startDate, $endDate])
                    ->groupBy('users.name', DB::raw('DATE(orders.created_at)'))
                    ->orderBy('order_date')
                    ->get()
                    ->groupBy('cashier_name');
            
                // Generate date range
                $dates = [];
                $currentDate = Carbon::parse($startDate);
                $endDateObj = Carbon::parse($endDate);
                
                while ($currentDate <= $endDateObj) {
                    $dates[] = $currentDate->format('Y-m-d');
                    $currentDate->addDay();
                }

                $selectedWeek = $request->input('week', 1);
                $year = $request->input('year', Carbon::now()->year);
                $month = $request->input('month', Carbon::now()->month);
            
                // Generate week options
                $weekOptions = [];
                $date = Carbon::createFromDate($year, $month, 1);
                
                for ($i = 1; $i <= 6; $i++) {
                    $start = $date->copy()
                        ->startOfWeek(Carbon::MONDAY)
                        ->format('M d');
                        
                    $end = $date->copy()
                        ->endOfWeek(Carbon::SUNDAY)
                        ->format('M d');
                        
                    $weekOptions[$i] = [
                        'start' => $start,
                        'end' => $end
                    ];
                    
                    $date->addWeek();
                }




    $activities = Activity::latest()->limit(5)->get(); // Fetch last 5 logs

        return view('supervisor.analytics', compact('transactions', 'cashierBills', 'startDate', 'endDate', 'productSales', 'orderStats', 'dailyOrders', 'dates','weekOptions', 'selectedWeek', 'year', 'month','activities'));
    }

    public function show($orderId)
    {
        try {
            // Explicitly find the order with relationships
            $order = Order::with(['cashier', 'orderDetails.product'])
                        ->findOrFail($orderId);

            // Calculate total price safely
            $totalPrice = $order->orderDetails->sum(function($detail) {
                return $detail->quantity * ($detail->product->price ?? 0);
            });

            return response()->json([
                'order' => $order,
                'total_price' => $totalPrice,
                'order_details' => $order->orderDetails
            ]);

        } catch (\Exception $e) {
            \Log::error("API Error: " . $e->getMessage());
            return response()->json([
                'error' => 'Order not found or invalid request',
                'message' => $e->getMessage()
            ], 404);
        }
    }


}
