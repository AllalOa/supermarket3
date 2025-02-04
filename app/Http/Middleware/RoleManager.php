<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        // Check the user's role and allow access accordingly
        switch ($role) {
            case 'supervisor':
                if ($userRole == 0) {
                    return $next($request);
                }
                break;
            case 'magazinier':
                if ($userRole == 1) {
                    return $next($request);
                }
                break;
            case 'cashier':
                if ($userRole == 2) {
                    return $next($request);
                }
                break;
        }

        // If role doesn't match, redirect to their dashboard
        switch ($userRole) {
            case 0:
                return redirect()->route('supervisor.dashboard');
            case 1:
                return redirect()->route('magazinier.dashboard');
            case 2:
                return redirect()->route('cashier.dashboard');
            default:
                return redirect()->route('login'); // In case the role doesn't match any defined ones
        }
    }
}
