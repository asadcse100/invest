<?php

namespace App\Http\Middleware;

use App\Enums\UserRoles;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        $sys = 'sys_tem_';
        $adminRoles = [ UserRoles::SUPER_ADMIN, UserRoles::ADMIN ];
        $systemErrs = $request->session()->has(str_replace(['_', '-'], '', $sys).'_er'.'ror');

        if (!in_array(Auth::user()->role, $adminRoles)) {
            Auth::logout();
            return redirect()->route('auth.login.form');
        }

        // if ( !($request->is('admin/'.str_replace(['_', '-'], '', $sys).'-status') || $request->is('admin/setup/'.str_replace(['_', '-'], '', $sys))) && 
        //     ((!system_admin_setup(str_replace(['_', '-'], '', $sys)) && $systemErrs == true) || !str_con()) ) {
        //     if (!str_con()) {
        //         return redirect()->route('adm'.'in.quick.reg'.'ist'.'er')->with(['notice' => str_replace(['_', '-'], '', $sys)]);   
        //     }
        // }

        return $next($request);
    }
}
