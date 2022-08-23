<?php

namespace App\Http\Middleware;

use App\Enums\UserRoles;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if (Auth::user()->role === UserRoles::ADMIN || Auth::user()->role === UserRoles::SUPER_ADMIN) {
                return redirect(route('admin.dashboard'));
            }
            if (Auth::user()->role === UserRoles::USER) {
                return redirect(route('dashboard'));
            }
        }

        return $next($request);
    }
}
