<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyInstallation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!file_exists(storage_path('installed')) && !$this->installationRoute($request)) {
            return redirect()->route('LaravelInstaller::welcome');
        }
        return $next($request);
    }

    private function installationRoute(Request $request)
    {
        $installerNamespace = 'LaravelInstaller::';
        $routes = [
            'welcome', 'environment', 'environmentWizard',
            'environmentSaveWizard', 'environmentClassic', 'environmentSaveClassic',
            'environmentManual', 'environmentSaveManual', 'requirements',
            'permissions', 'database', 'final'
        ];

        return !empty(array_filter($routes, function ($route) use ($installerNamespace, $request) {
            if ($request->routeIs($installerNamespace . $route)) {
                return $route;
            }
        }));
    }
}
