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
    $router->post('generate-otp', 'AuthController@generateOTP');
    $router->post('login', 'AuthController@login');

    $router->group(['middleware' => 'jwtverification'], function()use ($router)  {
        $router->post('generate-qr', 'QRController@QRgenerate');
        $router->get('get-templates', 'QRController@getTemplates');
    $router->post('logout', 'AuthController@logout');
});

});