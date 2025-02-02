<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$role): Response
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }

        $AuthUserRole = Auth::user()->role;

        switch($role) {
            case 'supervisor':
                if($AuthUserRole == 0) {
                    return $next($request);
                }
                break;
            case 'magazinier':
                if($AuthUserRole == 1) {
                    return $next($request);
                }
                break;

                case 'cashier':
                    if($AuthUserRole == 2) {
                        return $next($request);
                    }
                    break;
            }

            switch($AuthUserRole){
                case 0:
                    return redirect()->route('supervisor.dashboard');
                    break;
                case 1:
                    return redirect()->route('magazinier.dashboard');
                    break;

                    case 2:
                        return redirect()->route('cashier.dashboard');
                        break;
                
            }

           
        }
    }

