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

  Route::get('buku-besar/', 'API\GeneralLedgerController@index');

  Route::get('neraca-saldo/', 'API\TrialBalanceController@index');
  Route::get('neraca-saldo/export/', 'API\TrialBalanceController@export');

  Route::get('laporan-laba-rugi/', 'API\FinancialReportController@incomeStatement');
  Route::get('laporan-laba-rugi/export/', 'API\FinancialReportController@incomeStatementExport');
  
  Route::get('perubahan-ekuitas/', 'API\FinancialReportController@changeInEquity');
  Route::get('perubahan-ekuitas/export/', 'API\FinancialReportController@changeInEquityExport');
  
  Route::get('laporan-neraca/', 'API\FinancialReportController@balanceSheet');
  Route::get('laporan-neraca/export', 'API\FinancialReportController@balanceSheetExport');

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

  Route::get('akun-anggaran', 'API\BudgetAccountController@index');
  Route::post('akun-anggaran', 'API\BudgetAccountController@store');
  Route::post('akun-anggaran/{id}', 'API\BudgetAccountController@update');
  Route::delete('akun-anggaran/{id}', 'API\BudgetAccountController@destroy');

  Route::get('rencana-anggaran', 'API\BudgetPlanController@index');
  Route::post('rencana-anggaran', 'API\BudgetPlanController@store');
  Route::post('rencana-anggaran/update', 'API\BudgetPlanController@update');
  Route::delete('rencana-anggaran/{id}', 'API\BudgetPlanController@destroy');

  Route::get('realisasi-anggaran', 'API\BudgetRealizationController@index');
  Route::post('realisasi-anggaran', 'API\BudgetRealizationController@store');
  Route::post('realisasi-anggaran/{id}', 'API\BudgetRealizationController@update');
  Route::delete('realisasi-anggaran/{id}', 'API\BudgetRealizationController@destroy');

});