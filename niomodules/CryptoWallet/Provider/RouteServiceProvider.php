<?php

namespace NioModules\CryptoWallet\Provider;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */

    protected $nioModuleNameSpace = '\NioModules\CryptoWallet\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     * @version 1.0.0
     * @since 1.0
     */
    public function map()
    {
        $this->mapModuleRoutes();
    }

    protected function mapModuleRoutes()
    {
        Route::middleware(['web', 'auth'])
            ->namespace($this->nioModuleNameSpace)
            ->group(__DIR__ . '/../Routes/route.php');
    }
}
