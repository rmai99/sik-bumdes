<?php


use RealRashid\SweetAlert\Facades\Alert;

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
/* ======== DASHBOARD ======== */
Route::resource('dashboard', 'DashboardController');

/* ======== ACCOUNT ======== */
Route::resource('/akun', 'AccountController');
// Route::delete('/akun/{id}', 'AccountController@destroy');
Route::get('detailAccount', 'AccountController@detailAccount');

/* ======== CLASSIFICATION ======== */
Route::resource('classification', 'ClassificationController');
Route::get('detailClassification', 'ClassificationController@detailClassification');
Route::get('findClassification', 'ClassificationController@findClassification');

/* ======== INITIAL BALANCE ======== */
Route::resource('neraca_awal', 'InitialBalanceController');
Route::get('detail_balance', 'InitialBalanceController@detailBalance');

/* ======== TRIAL BALANCE ======== */
Route::resource('neraca_saldo', 'TrialBalanceController');

/* ======== GENERAL JOURNAL ======== */
Route::resource('jurnal_umum', 'GeneralJournalController')->except('update');
Route::put('jurnal_umum/update', 'GeneralJournalController@update')->name('jurnal.update');;

/* ======== GENERAL LEDGER ======== */
Route::resource('buku_besar', 'GeneralLedgerController');

/* ======== GENERAL LEDGER ======== */
Route::get('laporan_laba_rugi', 'FinancialReportController@incomeStatement');
Route::get('perubahan_ekuitas', 'FinancialReportController@changeInEquity');
Route::get('neraca', 'FinancialReportController@balanceSheet');

/* ======== GENERAL LEDGER ======== */
Route::resource('karyawan','EmployeeController');
Route::get('detailEmployee', 'EmployeeController@detailEmployee');

/* ======== BISNIS ======== */
Route::resource('bisnis', 'BusinessController');
Route::get('detail_bisnis', 'BusinessController@detailBusiness');
Route::middleware('auth')->get('set_business/{id}', 'BusinessController@setBusiness')->name('setBusiness');

/* ======== PROFILE ======== */
Route::resource('profile', 'ProfileController');

Route::get('admin/login', function () {
    
    return view('user/auth/login');
});

Route::get('/upgrade', function () {
    
    return view('user/upgrade');
});

Route::get('/', function () {

    return view('welcome');
    
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
