<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/import', 'EventController@import');
Route::get('/events', 'EventController@index');
Route::get('/event/{id}', 'EventController@detail');

Route::get('/categories', 'CategoryController@index');
Route::get('/category/{id}/events', 'CategoryController@events');

Route::get('/dooropen', 'EventController@dooropen');

Route::get('/test', 'EventController@test');