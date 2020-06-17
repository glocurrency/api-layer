<?php

namespace Glocurrency\ApiLayer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;

class ApiLayerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerMigrations();

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'apilayer-migrations');

            $this->publishes([
                __DIR__.'/../config/apilayer.php' => config_path('apilayer.php'),
            ], 'apilayer-config');

            $this->commands([
                Console\PassportCommand::class,
            ]);
        }
    }

    /**
     * Register migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if (ApiLayer::$runsMigrations) {
            return $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/apilayer.php', 'apilayer');
    }
}
