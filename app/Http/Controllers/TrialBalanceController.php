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
    public function index()
    {
        if (isset($_GET['year'])) {
            $year = $_GET['year'];
        } else {
            $year = date('Y');
        }

        $role = Auth::user();
        $isOwner = $role->hasRole('owner');
        
        $user = Auth::user()->id;

        if($isOwner){
            $session = session('business');

            $companies = Companies::where('id_user', $user)->first();
            // $test = Companies::where('is_actived' , 0)->get();
            // dd($test);
            $company = $companies->id;
            
            $business = Business::where('id_company', $company)->get();

            $getBusiness = Business::where('id_company', $company)->first()->id;
            
            if($session == 0){
                $session = $getBusiness;
            }

        } else {
            $getBusiness = Employee::where('id_user', $user)->select('id_business')->first()->id_business;
            
            $session = $getBusiness;
        }
        
        $save =  array();
        $i = 0;
        $parents = AccountParent::with('classification.parent')
        ->where('id_business', $session)->get();
        
        foreach($parents as $p){
            $save[$i]['parent_id'] = $p->id;
            $save[$i]['parent_name'] = $p->parent_name;

            $classification = $p->classification()->get();
            $j = 0;
            foreach($classification as $c){
                $save[$i]['classification'][$j]['classification_id'] = $c->id;
                $save[$i]['classification'][$j]['classification_name'] = $c->classification_name;

                $account = $c->account()->with('initialBalance', 'journal')->get();
                $k = 0;
                foreach($account as $a){
                    $save[$i]['classification'][$j]['account'][$k]['account_id'] = $a->id;
                    $save[$i]['classification'][$j]['account'][$k]['account_name'] = $a->account_name;
                    $save[$i]['classification'][$j]['account'][$k]['account_code'] = $a->account_code;
                    $save[$i]['classification'][$j]['account'][$k]['position'] = $a->position;

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
                    $save[$i]['classification'][$j]['account'][$k]['saldo_akhir'] = $ending_balance;
                    
                    $k++;
                }

                $j++;
            }
            
            $i++;
        }
        // dd($save);

        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->selectRaw('YEAR(date) as year')
        ->orderBy('date', 'desc')
        ->distinct()
        ->get();
        
        return view('user.neracaSaldo', compact('save','years', 'year', 'business', 'session'));
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
