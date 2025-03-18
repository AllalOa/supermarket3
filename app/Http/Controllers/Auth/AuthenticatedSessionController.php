<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Helpers\ActivityHelper;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        
      $authuserrole = Auth::user()->role;

      $user = Auth::user();
      $role = $user->role;
  
      // Define different log messages based on role
      $logMessage = match ($role) {

        

        0=> "the Admin logged in",

          1 => "the Magazinier just logged in",
          2 => "the Cashier just opened the store",
         
      };
      ActivityHelper::log($logMessage);
      
      if($authuserrole == 0) {
          return redirect()->intended(route('supervisor.dashboard', absolute: false));
        
      } else {
        
          return redirect()->intended(route('magazinier.dashboard', absolute: false));
        
      }

  
        return redirect()->intended(route('cashier.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {

        $user = Auth::user();

        if ($user) {
            $role = $user->role;
    
            // Define different logout messages based on role
            $logMessage = match ($role) {
                
                1 => "the Magazinier just logged out",
                2 => "the Cashier just closed the store",
           
            };
    
            // Log the activity
            ActivityHelper::log($logMessage);
        }




        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
