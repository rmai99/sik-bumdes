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
    public function index()
    {
        $user = Auth::user()->id;
        
        $role = Auth::user();
        $isOwner = $role->hasRole('owner');

        if($isOwner){
            $session = session('business');

            $company = Companies::where('id_user', $user)->first()->id;

            $getBusiness = Business::where('id_company', $company)->first()->id;
            if($session == 0){
                $session = $getBusiness;
            }

            $account = Account::with('classification.parent')
            ->whereHas('classification.parent.business', function ($q) use ($session){
                $q->where('id_business', $session);
            })->first()->id;
            
            //Sidebar
            $business = Business::where('id_company', $company)->get();

        } else {
            $idAccess = Employee::where('id_user', $user)->first()->id_business;
            
            $account = Account::with('classification.parent')
            ->whereHas('classification.parent.business', function ($q) use ($idAccess){
                $q->where('id_business', $idAccess);
            })->first()->id;

            $session = $idAccess;
            
        }
        
        //Parameter dalam Index
        if(isset($_GET['year'], $_GET['akun'])){
            $year = $_GET['year'];
            $akun = $_GET['akun'];
        } else {
            $year = date('Y');
            $akun = $account;
        }

        $checkAccount = Account::with('initialBalance', 'journal', 'classification.parent')
        ->whereHas('classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->where('id', $akun)
        ->get();

        
        $log = array();

        $i = 0;
        foreach($checkAccount as $balance){
            $i++;
            if(!$balance->initialBalance()->whereYear('date', $year)->first()){
                $beginning_balance = 0;

            } else {
                $beginning_balance = $balance->initialBalance()->whereYear('date', $year)->first()->amount;
            }
            $log['nama_akun'][] = $balance->account_name;
            $log['position'][] = $balance->position;
            $log['saldo_awal'][] = $beginning_balance;
            $log['position'][] = $balance->position;
            $log['kode_akun'][] = $balance->account_code;
            if(!$balance->initialBalance()->whereYear('date', $year)->first()){
                $log['date'][] = '';    
            } else {
                $log['date'][] = $balance->initialBalance()->first()->date;
            }

        }

        $data = GeneralJournal::with('account.classification.parent', 'detail')
        ->whereHas('account.classification.parent', function($q) use($session){
            $q->where('id_business', $session);
        })->whereHas('detail', function($q) use($year){
            $q->whereYear('date', $year);
        })
        ->where('id_account', $akun)
        ->get();

        $years = DetailJournal::selectRaw('YEAR(date) as year')
        ->orderBy('date', 'desc')
        ->distinct()
        ->get();

        $akuns = Account::with('classification.parent')
        ->whereHas('classification.parent', function ($q) use ($session){
            $q->where('id_business', $session);
        })->get();

        return view('user.bukuBesar', compact('business', 'data', 'years', 'akuns', 'account','log', 'session'));
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
