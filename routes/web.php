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
Route::middleware('auth')->resource('dashboard', 'DashboardController');
Route::middleware('auth')->get('/monthly-income/{date?}', 'DashboardController@getMonthly')->name('income_statement');
Route::middleware('auth')->get('/get_monthly_cash_flow/{year?}', 'DashboardController@get_monthly_cash_flow')->name('cash_flow');
Route::middleware('auth')->get('/get_daily_cash_flow/{date?}', 'DashboardController@get_daily_cash_flow')->name('cash_flow_daily');

/* ======== ACCOUNT ======== */
Route::resource('/akun', 'AccountController');
Route::middleware('auth')->get('detailAccount', 'AccountController@detailAccount');

/* ======== CLASSIFICATION ======== */
Route::middleware('auth')->resource('classification', 'ClassificationController');
Route::middleware('auth')->get('detailClassification', 'ClassificationController@detailClassification');
Route::middleware('auth')->get('findClassification', 'ClassificationController@findClassification');

/* ======== INITIAL BALANCE ======== */
Route::middleware('auth')->resource('neraca_awal', 'InitialBalanceController');
Route::middleware('auth')->get('detail_balance', 'InitialBalanceController@detailBalance');

/* ======== TRIAL BALANCE ======== */
Route::middleware('auth')->resource('neraca_saldo', 'TrialBalanceController');

/* ======== GENERAL JOURNAL ======== */
Route::middleware('auth')->resource('jurnal_umum', 'GeneralJournalController')->except('update');
Route::middleware('auth')->put('jurnal_umum/update', 'GeneralJournalController@update')->name('jurnal.update');;
Route::middleware('auth')->get('detailJournal', 'GeneralJournalController@detailJournal');

/* ======== GENERAL LEDGER ======== */
Route::middleware('auth')->resource('buku_besar', 'GeneralLedgerController');

/* ======== REPORT ======== */
Route::middleware('auth')->get('laporan_laba_rugi', 'FinancialReportController@incomeStatement');
Route::middleware('auth')->get('perubahan_ekuitas', 'FinancialReportController@changeInEquity');
Route::middleware('auth')->get('neraca', 'FinancialReportController@balanceSheet');

/* ======== EMPLOYEE ======== */
Route::middleware('auth')->resource('karyawan','EmployeeController');
Route::middleware('auth')->get('detailEmployee', 'EmployeeController@detailEmployee');

/* ======== BISNIS ======== */
Route::middleware('auth')->resource('bisnis', 'BusinessController');
Route::middleware('auth')->get('detail_bisnis', 'BusinessController@detailBusiness');
Route::middleware('auth')->get('set_business/{id}', 'BusinessController@setBusiness')->name('setBusiness');

/* ======== PROFILE ======== */
Route::middleware('auth')->resource('profile', 'ProfileController')->except('update');
Route::middleware('auth')->put('profile/update', 'ProfileController@update')->name('profile.update');
Route::middleware('auth')->put('profile/karyawan/update', 'ProfileController@updateEmployee')->name('profile_karyawan.update');
Route::middleware('auth')->get('cekpro', 'ProfileController@cekpro');

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('admin')->name('admin.')->group(function(){
    Route::resource('/', 'AdminDashboardController');
    Route::get('/user/user_register/{year?}', 'AdminDashboardController@user_register')->name('user_register');
    Route::resource('/user', 'UserMgtController');
});

Route::get('/admin/manajemen_admin', function () {
    
    return view('admin.admin');
});

Route::get('/admin/tambah_admin', function () {
    
    return view('admin.tambahAdmin');
});

Route::get('/', function () {

    return view('welcome');
    
});
Auth::routes();
