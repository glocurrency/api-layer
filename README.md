# Api Layer

<p align="center">
<a href="https://packagist.org/packages/glocurrency/api-layer"><img src="https://poser.pugx.org/glocurrency/api-layer/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/glocurrency/api-layer"><img src="https://poser.pugx.org/glocurrency/api-layer/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/glocurrency/api-layer"><img src="https://poser.pugx.org/glocurrency/api-layer/license.svg" alt="License"></a>
</p>

## Installation

```
composer require glocurrency/api-layer
```

### Passport
This module use <a href="https://github.com/laravel/passport">Laravel Passport</a> heavily. In order to make it work properly, follow the steps:
1. Run 
```
php artisan glo:apilayer:passport
```

This command will:
- generate the keys
- copy the migrations from passport
- replace both `client_id` and `user_id` columns in migrations with UUID.

2. Copy the passport middlewares to the `$routeMiddleware` in your `app/Http/Kernel.php` file:

```php
'client' => \Laravel\Passport\Http\Middleware\CheckClientCredentials::class,
'scopes' => \Laravel\Passport\Http\Middleware\CheckScopes::class,
'scope' => \Laravel\Passport\Http\Middleware\CheckForAnyScope::class,
```

3. Add the following code to the `boot` method of `AuthServiceProvider`:

```php
use Glocurrency\ApiLayer\ApiLayer;

public function boot()
{
    $this->registerPolicies();

    ApiLayer::passport();
}
```

4. Generate both <a href="https://laravel.com/docs/master/passport#client-credentials-grant-tokens">Client</a> and <a href="https://laravel.com/docs/master/passport#creating-a-personal-access-client">Personal</a> grant tokens.

```
php artisan passport:client --client
php artisan passport:client --personal
```

After creating your personal access client, place the client's ID and plain-text secret value in your application's `.env` file:

```ini
PASSPORT_PERSONAL_ACCESS_CLIENT_ID=client-id-value
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=unhashed-client-secret-value
```

## License

Glocurrency Api Layer is open-sourced software licensed under the [MIT license](LICENSE).
