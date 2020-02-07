<?php

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

$router->group(['prefix' => 'v1'], function() use($router) {
    $router->post('callback', ['as' => 'callback', 'uses' => 'CallBackController']);
    $router->group(['middleware' => 'auth'], function () use ($router) {
        
    });

    $router->post('register', ['as' => 'register', 'uses' => 'RegisterController']);
    $router->post('authenticate', ['as' => 'authenticate', 'uses' => 'AuthController']);
});
