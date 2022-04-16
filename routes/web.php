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

$router->post('auth/login', 'AuthController@authenticate');
$router->post('auth/register', 'AuthController@register');
