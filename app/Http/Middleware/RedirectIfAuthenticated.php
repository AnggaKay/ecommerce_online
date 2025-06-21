<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Jika pengguna mencoba mengakses halaman login
                if ($request->routeIs('login') || $request->is('login')) {
                    // Jika pengguna adalah admin, arahkan ke dashboard admin
                    if (Auth::guard($guard)->user()->isAdmin()) {
                        return redirect()->route('admin.dashboard');
                    }
                    
                    // Jika bukan admin, arahkan ke halaman utama
                    return redirect(RouteServiceProvider::HOME);
                }
            }
        }

        return $next($request);
    }
}
