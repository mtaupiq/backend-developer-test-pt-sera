<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
// Route for generate secret key
$router->get('/secretkey', function(){
    $key = bin2hex(random_bytes(16));
    return $key;
});

$router->post('/reqres/register', 'IntegrasiController@register');
$router->post('/reqres/login', 'IntegrasiController@login');

$router->get('/billdetails', 'BillingController@billdetails');

$router->group(
    [
        'prefix' => 'api',
    ],
    function() use ($router) {
        $router->post('login', 'AuthController@authenticate');
        $router->post('register', 'AuthController@register');
        
        $router->post('logout', [
            'middleware' => 'jwt.auth',
            'uses' => 'AuthController@logout'
            ]);
            
        $router->group(
            [
                'middleware' => 'jwt.auth',
                'prefix' => 'posts/mongodb'
            ],
            function() use ($router) {
                $router->get('/','PostController@index');
                $router->post('/create','PostController@create');
                $router->get('/{id}','PostController@show');
                $router->put('/update/{id}','PostController@update');
                $router->delete('/{id}','PostController@delete');
            }
        );

        $router->group(
            [
                'middleware' => 'jwt.auth',
                'prefix' => 'posts/firebase'
            ],
            function() use ($router) {
                $router->get('/','FirebaseController@index');
                $router->post('/create','FirebaseController@create');
                $router->get('/{id}','FirebaseController@show');
                $router->put('/update/{id}','FirebaseController@update');
                $router->delete('/{id}','FirebaseController@delete');
            }
        );
    }
);
