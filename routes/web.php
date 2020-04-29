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
Route::get('/monthly-income/{date?}', 'DashboardController@getMonthly')->name('income_statement');
Route::get('/get_monthly_cash_flow/{year?}', 'DashboardController@get_monthly_cash_flow')->name('cash_flow');
Route::get('/get_daily_cash_flow/{date?}', 'DashboardController@get_daily_cash_flow')->name('cash_flow_daily');

/* ======== ACCOUNT ======== */
Route::resource('/akun', 'AccountController');
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
Route::get('detailJournal', 'GeneralJournalController@detailJournal');

/* ======== GENERAL LEDGER ======== */
Route::resource('buku_besar', 'GeneralLedgerController');

/* ======== REPORT ======== */
Route::get('laporan_laba_rugi', 'FinancialReportController@incomeStatement');
Route::get('perubahan_ekuitas', 'FinancialReportController@changeInEquity');
Route::get('neraca', 'FinancialReportController@balanceSheet');

/* ======== EMPLOYEE ======== */
Route::resource('karyawan','EmployeeController');
Route::get('detailEmployee', 'EmployeeController@detailEmployee');

/* ======== BISNIS ======== */
Route::resource('bisnis', 'BusinessController');
Route::get('detail_bisnis', 'BusinessController@detailBusiness');
Route::get('set_business/{id}', 'BusinessController@setBusiness')->name('setBusiness');

/* ======== PROFILE ======== */
Route::resource('profile', 'ProfileController')->except('update');
Route::put('profile/update', 'ProfileController@update')->name('profile.update');
Route::put('profile/karyawan/update', 'ProfileController@updateEmployee')->name('profile_karyawan.update');
Route::get('cekpro', 'ProfileController@cekpro');

Route::get('/home', 'HomeController@index')->name('home');


Route::prefix('admin')->middleware('auth')->name('admin.')->group(function(){
    Route::resource('/', 'AdminDashboardController');
    Route::get('user/user_register', 'AdminDashboardController@user_register')->name('user_register');
    Route::resource('/user', 'UserMgtController');
    Route::resource('/manajemen_admin', 'AdminMgtController');
});

Route::get('/', function () {

    return view('welcome');
    
});
Auth::routes();
