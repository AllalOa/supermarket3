<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function storeCashier(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 2,
            'phone' => $request->phone,
        ]);
        
        return redirect()->route('add.cashier')->with('success', 'Cashier added successfully!');
    }

    public function storeMagazinier(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 1,
            'phone' => $request->phone,
        ]);
        
        return redirect()->route('add.magazinier')->with('success', 'Magazinier added successfully!');
    }
}
