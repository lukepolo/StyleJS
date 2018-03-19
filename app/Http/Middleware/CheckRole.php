<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $user = $request->user();

        if (! empty($user)) {
            if (! $user->hasRole($role)) {
                return response()->json('Not Authorized.', 401);
            }

            return $next($request);
        }

        return response()->json('Not Authorized.', 401);
    }
}
