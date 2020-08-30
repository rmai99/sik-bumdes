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

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

Route::middleware('auth:api')->group(function(){
  // Modul User
  Route::post('user/update', 'API\UserController@update');
  Route::get('user', 'API\UserController@index');
  Route::get('user/business', 'API\UserController@getBusiness');
  Route::post('user/session', 'API\UserController@setSession');
  
  Route::get('neraca-awal/', 'API\InitialBalanceController@index');
  Route::post('neraca-awal/', 'API\InitialBalanceController@store');
  Route::post('neraca-awal/{id}', 'API\InitialBalanceController@update');
  Route::delete('neraca-awal/{id}', 'API\InitialBalanceController@destroy');

  Route::get('jurnal-umum/', 'API\GeneralJournalController@index');
  Route::post('jurnal-umum/', 'API\GeneralJournalController@store');
  Route::post('jurnal-umum/{id}', 'API\GeneralJournalController@update');
  Route::delete('jurnal-umum/{id}', 'API\GeneralJournalController@destroy');

  Route::get('parent/', 'API\ParentController@index');
  Route::get('parent/child', 'API\ParentController@indexChild');

  Route::get('classification', 'API\ClassificationController@index');
  Route::get('classification/{id}', 'API\ClassificationController@parent');
  Route::post('classification', 'API\ClassificationController@store');
  Route::post('classification/{id}', 'API\ClassificationController@update');
  Route::delete('classification/{id}', 'API\ClassificationController@destroy');

  Route::get('account', 'API\AccountController@index');
  Route::get('account/{id}', 'API\AccountController@classification');
  Route::post('account', 'API\AccountController@store');
  Route::post('account/{id}', 'API\AccountController@update');
  Route::delete('account/{id}', 'API\AccountController@destroy');

});