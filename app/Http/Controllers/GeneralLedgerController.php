<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
use App\Companies;
use App\Business;
use App\Account;
use App\GeneralJournal;
use App\DetailJournal;
use App\Employee;

class GeneralLedgerController extends Controller
{

    public function __construct()
    {
        $this->middleware(['role:company|employee']);

        $this->middleware('auth');
        
    }
    
    public function index()
    {
        $user = Auth::user();
        $isCompany = $user->hasRole('company');
        if($isCompany){
            $session = session('business');
            $company = Companies::where('id_user', $user->id)->first()->id;
            $business = Business::where('id_company', $company)->get();
            if($session == null){
                $session = Business::where('id_company', $company)->first()->id;
            }
            $getBusiness = Business::with('company')
            ->where('id_company', $company)->where('id', $session)->first();
        } else {
            $getBusiness = Employee::with('business')->where('id_user', $user->id)->first();
            $session = $getBusiness->id_business;
        }
        $account = Account::with('classification.parent')
        ->whereHas('classification.parent.business', function ($q) use ($session){
            $q->where('id_business', $session);
        })->first()->id;

        if(isset($_GET['year'], $_GET['akun'])){
            $year = $_GET['year'];
            $akun = $_GET['akun'];
        } else {
            $year = date('Y');
            $akun = $account;
        }
        // Menampilkan data detail akun dan neraca awalnya
        $checkAccount = Account::with('initialBalance', 'journal', 'classification.parent')
        ->whereHas('classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->where('id', $akun)->first();  

        $log = array();
        if(!$checkAccount->initialBalance()->whereYear('date', $year)->first()){
            $beginning_balance = 0;
        } else {
            $beginning_balance = $checkAccount->initialBalance()->whereYear('date', $year)->first()->amount;
        }
        $log['nama_akun'] = $checkAccount->account_name;
        $log['position'] = $checkAccount->position;
        $log['saldo_awal'] = $beginning_balance;
        $log['position'] = $checkAccount->position;
        $log['kode_akun'] = $checkAccount->account_code;
        if(!$checkAccount->initialBalance()->whereYear('date', $year)->first()){
            $log['date'] = '';    
        } else {
            $log['date'] = $checkAccount->initialBalance()->first()->date;
        }

        $data = GeneralJournal::with('account.classification.parent', 'detail')
            ->whereHas('account.classification.parent', function($q) use($session){
                $q->where('id_business', $session);
            })->whereHas('detail', function($q) use ($year){
                $q->whereYear('date', $year);
            })->where('id_account', $akun)
        ->get();

        $data=$data->sortBy('detail.date');

        $years = DetailJournal::selectRaw('YEAR(date) as year')
        ->orderBy('date', 'desc')->distinct()->get();

        $akuns = Account::with('classification.parent')
        ->whereHas('classification.parent', function ($q) use ($session){
            $q->where('id_business', $session);
        })->get();

        return view('user.bukuBesar', compact('business', 'data', 'years', 'akuns', 'account','log', 'session', 'getBusiness'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
