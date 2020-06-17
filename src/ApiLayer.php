<?php

namespace Glocurrency\ApiLayer;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;

class ApiLayer
{
    /**
     * Indicates if ApiLayer migrations will be run.
     *
     * @var bool
     */
    public static $runsMigrations = true;

    /**
     * Configure ApiLayer to not register its migrations.
     *
     * @return static
     */
    public static function ignoreMigrations()
    {
        static::$runsMigrations = false;

        return new static;
    }

    /**
     * Binds the Passport routes and settings.
     *
     * @return void
     */
    public static function passport(array $tokensCan = [], array $scopes = [])
    {
        Passport::routes();

        Passport::personalAccessClientId(
            config('passport.personal_access_client.id')
        );

        Passport::personalAccessClientSecret(
            config('passport.personal_access_client.secret')
        );

        $cookieName = Str::slug(env('APP_NAME', 'glo_api_layer'), '_').'_token';
        Passport::cookie($cookieName);

        $defaultTokensCan = [
            'ping-api' => 'Return app name on success',
        ];

        Passport::tokensCan(array_merge($defaultTokensCan, $tokensCan));

        $defaultScopes = [
            'ping-api',
        ];

        Passport::setDefaultScope(array_merge($defaultScopes, $scopes));

        Passport::tokensExpireIn(now()->addDays(30));
        Passport::refreshTokensExpireIn(now()->addDays(60));
        Passport::personalAccessTokensExpireIn(now()->addYears(1000));
    }

    /**
     * Binds the ApiLayer routes into the controller.
     *
     * @return void
     */
    public static function adminRoutes($callback = null, array $options = [])
    {
        $callback = $callback ?: function ($router) {
            $router->all();
        };

        $defaultOptions = [
            'prefix' => 'admin',
            'namespace' => '\Glocurrency\ApiLayer\Http\Controllers\Admin',
        ];

        $options = array_merge($defaultOptions, $options);

        Route::group($options, function ($router) use ($callback) {
            $callback(new RouteRegistrar($router));
        });
    }
}
