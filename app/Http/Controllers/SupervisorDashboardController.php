<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;

class SupervisorDashboardController extends Controller
{
    public function index()
    {
        $totalCashiers = User::where('role', 2)->count();
        $totalMagaziniers = User::where('role', 1)->count();
        $totalProducts = Product::count();
        $lowStockItems = Product::where('quantity', '<=', 5)->count();

        return view('supervisor.dashboard', compact(
            'totalCashiers', 'totalMagaziniers', 'totalProducts', 'lowStockItems'
        ));
    }

    public function getCashiers()
    {
        $cashiers = User::where('role', 2)->get();
        return response()->json($cashiers);
    }
    

    public function getMagaziniers()
    {
        $magaziniers = User::where('role', 1)->get();
        return response()->json($magaziniers);
    }

    public function getLowStockItems(): JsonResponse
    {
        try {
            // Fetch products where quantity is low (e.g., less than 10)
            $lowStockItems = Product::where('quantity', '<', 10)->get();
    
            if ($lowStockItems->isEmpty()) {
                return response()->json(['message' => 'No low stock items found'], 200);
            }
    
            return response()->json($lowStockItems, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch data', 'details' => $e->getMessage()], 500);
        }
    }
}
