<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use \App\Http\Controllers\AttendeeController;
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
    return response()->json([
        "error" => "Bienvenido a nuestra API Dios Consejero",
        "authorization" => "Usted no tiene permisos",
        "code" => 404
    ], 404);
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/attendees', ['uses' => 'AttendeeController@index']);
    $router->post('/attendees', ['uses' => 'AttendeeController@store']);
    $router->get('/attendees/{id}', ['uses' => 'AttendeeController@show']);
    $router->put('/attendees/{id}', ['uses' => 'AttendeeController@update']);
    $router->delete('/attendees/{id}', ['uses' => 'AttendeeController@destroy']);
    $router->post('/decode', 'AttendeeController@decode');
});
