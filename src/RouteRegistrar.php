<?php

namespace Glocurrency\ApiLayer;

use Illuminate\Contracts\Routing\Registrar as Router;

class RouteRegistrar
{
    /**
     * The router implementation.
     *
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    /**
     * Create a new route registrar instance.
     *
     * @param  \Illuminate\Contracts\Routing\Registrar  $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Register routes for transient tokens, clients, and personal access tokens.
     *
     * @return void
     */
    public function all()
    {
        $this->forPing();
        $this->forUsers();
    }

    /**
     * Register the routes needed for ping.
     *
     * @return void
     */
    public function forPing()
    {
        $this->router->group(['middleware' => ['client:ping-api']], function ($router) {
            $router->get('/ping', [
                'as' => 'admin.ping',
                'uses' => 'PingController@ping',
            ]);
        });
    }

    /**
     * Register the routes needed for user management.
     *
     * @return void
     */
    public function forUsers()
    {
        $this->router->group(['prefix' => 'users', 'middleware' => ['client']], function ($router) {

            $router->get('/', [
                'as' => 'admin.users.all',
                'uses' => 'UserController@getUsers',
            ]);

            $router->post('/create', [
                'as' => 'admin.users.add',
                'uses' => 'UserController@addUser',
            ]);

            $router->group(['prefix' => '/{user_id}'], function ($router) {

                $router->get('/', [
                    'as' => 'admin.user.get_user',
                    'uses' => 'UserController@getUser',
                ]);

                $router->patch('/enable', [
                    'as' => 'admin.user.enable',
                    'uses' => 'UserController@enableUser',
                ]);

                $router->patch('/disable', [
                    'as' => 'admin.user.disable',
                    'uses' => 'UserController@disableUser',
                ]);

                $router->group(['prefix' => 'access_tokens'], function ($router) {

                    $router->get('/', [
                        'as' => 'admin.user.access_tokens.all',
                        'uses' => 'UserController@getAccessTokensForUser',
                    ]);

                    $router->post('/create', [
                        'as' => 'admin.user.access_tokens.create',
                        'uses' => 'UserController@addAccessTokenForUser',
                    ]);

                    $router->patch('/{access_token_id}/rewoke', [
                        'as' => 'admin.user.access_tokens.rewoke',
                        'uses' => 'UserController@rewokeAccessTokenForUser',
                    ]);

                });

            });

        });
    }
}
