<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        $user = $request->user();
        $permissionNames = array_filter(array_map('trim', explode('|', $permissions)));

        abort_unless(
            $user !== null
                && collect($permissionNames)->contains(
                    fn (string $permissionName): bool => $user->hasPermission($permissionName),
                ),
            403,
            'No tienes permiso para realizar esta acción.',
        );

        return $next($request);
    }
}
