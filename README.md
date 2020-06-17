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
This module use Laravel Passport heavily. In order to make it work properly, follow the steps:
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

## License

Glocurrency Api Layer is open-sourced software licensed under the [MIT license](LICENSE).
