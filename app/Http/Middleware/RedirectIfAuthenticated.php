<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->role && $user->role->name === 'Admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role && $user->role->name === 'Manager') {
                return redirect()->route('manager.dashboard');
            }

            // default: Penyewa
            return redirect()->route('dashboard.user');
        }

        return $next($request);
    }
}
