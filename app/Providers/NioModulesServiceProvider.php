<?php

namespace App\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\SplFileInfo;

class NioModulesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            if (!empty($modules = available_modules('mod'))) {
                foreach ($modules as $module) {

                    if (file_exists($provider = base_path(implode(DIRECTORY_SEPARATOR, ['niomodules', $module, 'Provider', 'RouteServiceProvider.php'])))) {
                        $this->app->register("\NioModules\\$module\Provider\RouteServiceProvider");
                    }

                    if (file_exists($filtering = base_path(implode(DIRECTORY_SEPARATOR, ['niomodules', $module, 'RequestFilters'])))) {
                        $fileSystem = new Filesystem();
                        collect($fileSystem->files($filtering))->each(function (SplFileInfo $item) use ($module) {
                            $alias = strtolower($module . '-' . $item->getBasename('Filter.php'));
                            $file = $item->getBasename('.php');
                            $this->app->make(Router::class)->aliasMiddleware($alias, "NioModules\\{$module}\\RequestFilters\\{$file}");
                        });
                    }

                    if (file_exists($views = base_path(implode(DIRECTORY_SEPARATOR, ['niomodules', $module, 'Views'])))) {
                        $this->loadViewsFrom($views, $module);
                    }

                    if (file_exists($config = base_path(implode(DIRECTORY_SEPARATOR, ['niomodules', $module, 'Config', 'module.php'])))) {
                        $this->mergeConfigFrom($config, 'modules');
                    }

                    if (file_exists($migrations = base_path(implode(DIRECTORY_SEPARATOR, ['niomodules', $module, 'Database', 'migrations'])))) {
                        $this->loadMigrationsFrom($migrations);
                    }

                    if (class_exists($moduleLoader = "\\NioModules\\{$module}\\{$module}Module")) {
                        $this->app->bind(strtolower($module), function () use ($moduleLoader) {
                            return new $moduleLoader();
                        });
                    }
                }
            }
        } catch (\Exception $e) {
            if (env('APP_DEBUG', false)) {
                save_error_log($e, 'module-service');
            }
        }
    }
}
