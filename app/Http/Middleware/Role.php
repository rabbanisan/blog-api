<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasRole($roles)) {
            abort(403, 'Forbidden: You do not have access.');
        }

        return $next($request);
    }
}
