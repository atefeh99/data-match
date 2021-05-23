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

$router->get('admin/{administrative_level}', [
    'middleware' => 'level',
    'uses' => 'MainController@adminList']);
$router->get('villages/not-matched','MainController@notMatchedVillages');
$router->post('villages/matched','MainController@storeMatchedVillages');
$router->get('villages/matched','MainController@getMatchedVillages');
$router->delete('villages/{id}','MainController@deleteMatchedVillages');
