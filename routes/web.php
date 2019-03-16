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

$router->group(['prefix' => 'api'], function () use ($router) {
	$router->get('user',  ['uses' => 'UserController@showAll']);
	$router->get('user/{id}', ['uses' => 'UserController@showOne']);
	$router->post('user', ['uses' => 'UserController@create']);
	$router->delete('user/{id}', ['uses' => 'UserController@delete']);
	//$router->put('user/{id}', ['uses' => 'UserController@update']);

	$router->post('login', ['uses' => 'UserController@login']);
	$router->post('logout', ['uses' => 'UserController@logout']);

	$router->get('user/{id}/registration', ['uses' => 'RegistrationController@showByUser']);
	$router->get('event/{id}/registration', ['uses' => 'RegistrationController@showByEvent']);

	$router->get('registration',  ['uses' => 'RegistrationController@showAll']);
	$router->get('registration/{id}', ['uses' => 'RegistrationController@showOne']);
	$router->post('registration', ['uses' => 'RegistrationController@create']);
	$router->delete('registration/{id}', ['uses' => 'RegistrationController@delete']);
	$router->put('registration/{id}/confirm', ['uses' => 'RegistrationController@confirm']);

	$router->get('team',  ['uses' => 'TeamController@showAll']);
	$router->get('team/{id}', ['uses' => 'TeamController@showOne']);
	$router->post('team', ['uses' => 'TeamController@create']);
	$router->delete('team/{id}', ['uses' => 'TeamController@delete']);
	$router->put('team/{id}/confirm', ['uses' => 'TeamController@confirm']);
});