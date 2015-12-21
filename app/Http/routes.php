<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Authentication routes...
Route::get('/', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::post('/update', 'PostController@store');
Route::get('/{name}', 'PostController@creatorsPosts');
Route::get('/{name}/{id}', 'PostController@creatorsPosts');
//Route::post('register', 'Auth\AuthController@postRegister');

// Route::get('/', function () {
//     return view('auth.signin');
// });
