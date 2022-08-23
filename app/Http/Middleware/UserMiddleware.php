<?php

namespace App\Http\Middleware;

use App\Enums\UserRoles;
use App\Enums\UserStatus;
use Closure;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->role != UserRoles::USER) {
            Auth::logout();
            return redirect()->route('auth.login.form');
        }

        if (mandatory_verify() && Auth::user()->status !== UserStatus::ACTIVE) {
            Auth::logout();
            return redirect()->route('auth.login.form');
        }

        return $next($request);
    }
}
