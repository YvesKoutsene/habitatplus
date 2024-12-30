<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    /*public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }*/

    public function handle(Request $request, Closure $next, ...$permissions)
    {
        foreach ($permissions as $permission) {
            if ($request->user() && $request->user()->can($permission)) {
                return $next($request);
            }
        }

        abort(403, 'Vous n\'avez pas les permissions nécessaires.');
    }

}
