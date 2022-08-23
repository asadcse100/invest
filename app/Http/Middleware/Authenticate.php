<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     * @version 1.0.0
     * @since 1.0
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('auth.login.form');
        }
    }
}
