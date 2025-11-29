<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PenyewaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $role = $request->user()?->role?->name;

        if (!in_array($role, ['User', 'Penyewa'], true)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
