<?php

namespace Glocurrency\ApiLayer\Models;

use GoldSpecDigital\LaravelEloquentUUID\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
        'hasAccessTokens' => 'boolean',
        'hasClients' => 'boolean',
    ];

    public function hasAccessTokens()
    {
        return $this->tokens()
            ->where('revoked', false)
            ->count() > 0;
    }

    public function hasClients()
    {
        return $this->clients()
            ->where('revoked', false)
            ->count() > 0;
    }
}
