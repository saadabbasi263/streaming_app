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
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('sendotp', 'AuthController@sendotp');
    $router->post('validateotp', 'AuthController@validateotp');
    $router->post('updatepassword', 'AuthController@updatepassword');

    $router->group(['middleware' => 'jwtverification'], function()use ($router)  {
        $router->get('userdetail', 'AuthController@userdetail');
        $router->post('updateuserdetail', 'AuthController@updateUserDetail');
        $router->post('logout', 'AuthController@logout');
});

});