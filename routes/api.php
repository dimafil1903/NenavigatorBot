<?php

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::middleware('api')->resource('/stat', "StatController");
Route::middleware('api')->resource('/grapics', "UserChartController");
Route::middleware('api')->resource('/chat', "ChatsController");
Route::middleware('api')->resource('/triggers', "TriggersController");

Route::post('login', 'APIController@login');
Route::post('register', 'APIController@register');
Route::get('getstatus', 'APIController@getStatus');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', 'APIController@logout');

    Route::post('tasks/create', "TaskController@create");
    Route::middleware('api')->resource('/tasks', "TaskController");
    Route::middleware('api')->resource('/categories', "TaskCategoryController");


});
