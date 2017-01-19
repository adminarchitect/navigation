<?php

namespace Terranet\Navigation;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Terranet\Navigation\Console\NavigationTableCommand;
use Terranet\Navigation\Console\ProviderMakeCommand;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        if (! defined('_TERRANET_NAVY_')) {
            define('_TERRANET_NAVY_', 1);
        }

        $baseDir = realpath(__DIR__ . '/..');

        /*
         * Publish & Load configuration
         */
        $this->publishes(["{$baseDir}/publishes/config.php" => config_path('navigation.php')], 'config');
        $this->mergeConfigFrom("{$baseDir}/publishes/config.php", 'navigation');

        /*
         * Publish & Load views, assets
         */
        $this->publishes(["{$baseDir}/publishes/views" => base_path('resources/views/vendor/navigation')], 'views');
        $this->loadViewsFrom("{$baseDir}/publishes/views", 'navigation');

        /*
         * Publish & Load translations
         */
        $this->publishes(
            ["{$baseDir}/publishes/lang" => base_path('resources/lang/vendor/navigation')],
            'translations'
        );
        $this->loadTranslationsFrom("{$baseDir}/publishes/lang", 'navigation');

        $this->publishes(
            [
                "{$baseDir}/publishes/Models" => app_path(),
                "{$baseDir}/publishes/Modules" => app_path(app('scaffold.config')->get('paths.module')),
                "{$baseDir}/publishes/Savers" => app_path(app('scaffold.config')->get('paths.saver')),
                "{$baseDir}/publishes/Templates" => app_path(app('scaffold.config')->get('paths.template')),
                "{$baseDir}/publishes/Providers" => app_path(config('navigation.paths.provider')),
            ],
            'boilerplate'
        );
        $this->publishes(["{$baseDir}/publishes/public" => public_path('navigation')], 'public');
    }

    public function register()
    {
        $this->app->bind('admin.navigation', function () {
            return new Manager(config('navigation.providers'));
        });

        $this->app->singleton('command.navigation.table', function ($app) {
            return new NavigationTableCommand($app['files'], $app['composer']);
        });

        $this->app->singleton('command.navigation.provider', function ($app) {
            return new ProviderMakeCommand($app['files'], $app['composer']);
        });

        $this->commands(['command.navigation.table', 'command.navigation.provider']);
    }
}
