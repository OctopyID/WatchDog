<?php

namespace Octopy\WatchDog\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WatchDogAbilityMiddleware
{
    /**
     * @param  Request $request
     * @param  Closure $next
     * @param  string  $abilities
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $abilities) : mixed
    {
        if ($user = $request->user()) {
            foreach (explode('|', $abilities) as $role) {
                if ($user->ability->able($role)) {
                    return $next($request);
                }
            }
        }

        abort(403);
    }
}
