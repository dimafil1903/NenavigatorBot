<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::view('/', 'app')->name('home');
Route::view('/welcome', 'app')->name('welcome');
Route::view('/game', 'app')->name('game');
Route::view('/stat', 'app')->name('statistics');
Route::view('/auth', 'app')->name('auth');
Route::view('/stat/{id}', 'app')->name('Solostatistics');
Route::post('/deltrigger', 'UserChartController@del');

Route::get('unset', 'TelegramController@unset');
Route::get('set', 'TelegramController@set');
Route::post('hook', 'TelegramController@hook');
Route::get('info', 'TelegramController@info');
//  Route::get('pay', 'LiqPayController@getform');


//Route::group(['prefix' => 'admin'], function () {
//    Voyager::routes();
//});
