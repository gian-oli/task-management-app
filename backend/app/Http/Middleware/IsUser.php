<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsUser
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->is_admin) {
            return response()->json(['message' => 'Forbidden. Users only.'], 403);
        }
        return $next($request);
    }
}
