<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
use App\Companies;
use App\Business;
use App\Account;
use App\InitialBalance;
use App\AccountParent;
use App\Employee;
use App\DetailJournal;

class TrialBalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:company|employee']);

        $this->middleware('auth');
        
    }
    
    public function index()
    {
        //Mengidentifikasi role user
        $role = Auth::user();
        $isCompany = $role->hasRole('company');
        $user = Auth::user()->id;

        if($isCompany){
            $session = session('business');
            $company = Companies::where('id_user', $user)->first()->id;
            $business = Business::where('id_company', $company)->get();
            if($session == null){
                $session = Business::where('id_company', $company)->first()->id;
            }
            $getBusiness = Business::with('company')
            ->where('id_company', $company)
            ->where('id', $session)->first();
        } else {
            $getBusiness = Employee::with('business')->where('id_user', $user)->first();
            $session = $getBusiness->id_business;
        }

        if (isset($_GET['year'])) {
            $year = $_GET['year'];
        } else {
            $year = date('Y');
        }

        $balance =  array();
        //Menghitung saldo akun
        $parents = AccountParent::with('classification.parent')
        ->where('id_business', $session)->get();
        $i = 0;
        foreach($parents as $p){
            $balance[$i]['parent_code'] = $p->parent_code;
            $balance[$i]['parent_name'] = $p->parent_name;

            $classification = $p->classification()->get();
            $j = 0;
            foreach($classification as $c){
                $balance[$i]['classification'][$j]['classification_id'] = $c->id;
                $balance[$i]['classification'][$j]['classification_name'] = $c->classification_name;

                $account = $c->account()->with('initialBalance', 'journal')->get();
                $k = 0;
                foreach($account as $a){
                    $balance[$i]['classification'][$j]['account'][$k]['account_id'] = $a->id;
                    $balance[$i]['classification'][$j]['account'][$k]['account_name'] = $a->account_name;
                    $balance[$i]['classification'][$j]['account'][$k]['account_code'] = $a->account_code;
                    $balance[$i]['classification'][$j]['account'][$k]['position'] = $a->position;

                    if(!$a->initialBalance()->whereYear('date', $year)->first()){
                        $beginning_balance = 0;
                    } else {
                        $beginning_balance = $a->initialBalance()->whereYear('date', $year)->first()->amount;
                    }
                    $position = $a->position;
                    $code = $a->numberCode;

                    if($a->journal()->exists()){
                        $ending_balance = $beginning_balance;
                        $journals = $a->journal()->whereHas('detail', function($q) use($year){
                            $q->whereYear('date', $year);
                        })->get();
                        foreach ($journals as $journal) {
                            if ($journal->position == $position) {
                                $ending_balance += $journal->amount;
                            }else {
                                $ending_balance -= $journal->amount;
                            }
                        }
                    }else {
                        if($a->initialBalance()->whereYear('date', $year)->first()){
                            $ending_balance = $beginning_balance;
                        } else {
                            $ending_balance = "0";
                        }
                    }
                    $balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'] = $ending_balance;
                    
                    $k++;
                }
                $j++;
            }
            $i++;
        }
        // dd($balance);
        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();
        
        return view('user.neracaSaldo', compact('balance','years', 'year', 'business', 'session','getBusiness'));
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
