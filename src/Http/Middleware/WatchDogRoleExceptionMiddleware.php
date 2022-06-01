<?php

namespace Octopy\WatchDog\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WatchDogRoleExceptionMiddleware
{
    /**
     * @param  Request $request
     * @param  Closure $next
     * @param  string  $roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $roles) : mixed
    {
        if ($user = $request->user()) {
            foreach (explode('|', $roles) as $role) {
                if (! $user->role->has($role)) {
                    return $next($request);
                }
            }
        }

        abort(403);
    }
}
