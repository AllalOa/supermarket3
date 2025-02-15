<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Promotion;
use App\Models\Product;
use Illuminate\Support\Facades\DB;



class SupervisorDashboardController extends Controller
{
    public function index()
    {
        $totalCashiers = User::where('role', 2)->count();
        $totalMagaziniers = User::where('role', 1)->count();
        $totalProducts = Product::count();
        $lowStockItems = Product::where('quantity', '<=', 5)->count();
        $totalPromotedUsers = Promotion::where('expired_at', NULL)->count();
        // Get promoted users whose 'end_date' is NULL
        $promotedUsers = Promotion::whereNull('expired_at')->with('user')->get();
    
        return view('supervisor.dashboard', compact(
            'totalCashiers','totalPromotedUsers', 'totalMagaziniers', 'totalProducts', 'lowStockItems', 'promotedUsers'
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

   

    public function promoteUser(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            $role = $request->input('role');
    
            // Validate that the user exists
            $user = User::find($userId);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found']);
            }
    
            // Insert into promotions table
            $promotion = new Promotion();
            $promotion->user_id = $userId;
            $promotion->previous_role = $role;
            $promotion->promoted_at = now();
            $promotion->save();
    
            // Update user role to 0
            $user->update(['role' => 0]);
    
            return response()->json(['success' => true]);
    
        } catch (\Exception $e) {
            Log::error('Promotion Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function demoteUser(Request $request)
    {
        $userId = $request->input('user_id');
    
        // Trouver l'enregistrement de promotion actif
        $promotion = Promotion::where('user_id', $userId)->whereNull('expired_at')->first();
    
        if (!$promotion) {
            return response()->json(['success' => false, 'message' => 'Promotion record not found']);
        }
    
        // Définir `expired_at` pour marquer la fin de la promotion
        $promotion->update(['expired_at' => now()]);
    
        // Trouver l'utilisateur concerné
        $user = User::find($userId);
        if ($user) {
            // Convertir le rôle texte en numéro pour la table `users`
            $previousRole = $promotion->previous_role;
            $roleMapping = [
                'magaziniers' => 1,
                'cashiers' => 2
            ];
    
            // Assigner l'ancien rôle s'il est reconnu
            if (isset($roleMapping[$previousRole])) {
                $user->update(['role' => $roleMapping[$previousRole]]);
            } else {
                return response()->json(['success' => false, 'message' => 'Invalid previous role']);
            }
        }
    
        return response()->json(['success' => true]);
    }
    

    public function getPromotedUsers()
    {
        $promotedUsers = Promotion::whereNull('expired_at')
            ->with('user') // Charge l'utilisateur associé
            ->get()
            ->map(function ($promotion) {
                $role = $promotion->user->role ?? 'Unknown';
    
                // Vérifier si le rôle est 0 et assigner 'admin'
                if ($role === 0) {
                    $role = 'admin';
                }
    
                return [
                    'id' => $promotion->user->id ?? null,
                    'name' => $promotion->user->name ?? 'Unknown',
                    'phone' => $promotion->user->phone ?? 'No phone number',
                    'role' => $role,
                ];
            });
    
        return response()->json($promotedUsers);
    }
    
    


 

}
