<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function storeCashier(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'required',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Handle Profile Picture Upload
        if ($request->hasFile('profile_picture')) {
            $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        } else {
            $imagePath = null;
        }
    
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 2,
            'phone' => $request->phone,
            'profile_picture' => $imagePath,
        ]);
    
        return redirect()->route('add.cashier')->with('success', 'Cashier added successfully!');
    }
    

    public function storeMagazinier(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:15',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $profilePicturePath = null;
        
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 1, // Magazinier role
            'phone' => $request->phone,
            'profile_picture' => $profilePicturePath,
        ]);

        return redirect()->route('add.magazinier')->with('success', 'Magazinier added successfully!');
    }
}
