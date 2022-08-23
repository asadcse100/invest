<?php

namespace App\Providers;

use App\Http\View\Composers\AdminSidebarComposer;
use App\Http\View\Composers\AdminWarningComposer;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(is_force_https()){
            URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);

        View::composer('admin.layouts.sidebar', AdminSidebarComposer::class);
        View::composer(['misc.message-admin'], AdminWarningComposer::class);
    }
}
