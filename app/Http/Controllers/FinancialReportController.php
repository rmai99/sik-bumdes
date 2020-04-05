<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
use App\Companies;
use App\Business;
use App\AccountParent;
use App\InitialBalance;
use App\Employee;

class FinancialReportController extends Controller
{
    public function incomeStatement()
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

        $parent = AccountParent::with('classification.account')
        ->where('id_business', $session)->get();

        $array = array();
        $arraybeban = array();
        $array_pendapatan_lainnya = array();
        $array_biaya_lainnya = array();
        $biaya = 0;
        $pendapatan = 0;

        foreach($parent as $p){
            $i = 0;
            $classification = $p->classification()->get();
            foreach($classification as $c){
                $account = $c->account()->get();
                foreach($account as $a){
                    $position = $a->position;

                    if(!$a->initialBalance()->whereYear('date', $year)->first()){
                        $saldo_awal = 0;
                    } else {
                        $saldo_awal = $a->initialBalance()->whereYear('date', $year)->first()->amount;
                    }

                    if($a->journal()->exists()){
                        $saldo_akhir = $saldo_awal;
                        $jurnals = $a->journal()->whereHas('detail', function($q) use($year){
                            $q->whereYear('date', $year);
                        })->get();
                        foreach($jurnals as $jurnal){
                            if ($jurnal->position == $position) {
                                $saldo_akhir += $jurnal->amount;
                            }else {
                                $saldo_akhir -= $jurnal->amount;
                            }
                        }
                    } else {
                        if($a->initialBalance()->whereYear('date', $year)->first()){
                            $saldo_akhir = $saldo_awal;
                            
                        } else {
                            $saldo_akhir = 0;
                        }
                    }
                    
                    if($p->parent_name == "Pendapatan"){
                        $array[$i]['class'] = $c->classification_name;
                        $array[$i]['nama'][] = $a->account_name;
                        $array[$i]['kode'][] = $a->account_code;
                        $array[$i]['saldo_akhir'][] = $saldo_akhir;
                    } 
                    else if($p->parent_name == "Beban"){
                        $bebanArray[$i]['class'] = $c->classification_name;
                        $bebanArray[$i]['nama'][] = $a->account_name;
                        $bebanArray[$i]['kode'][] = $a->account_code;
                        $bebanArray[$i]['saldo_akhir'][] = $saldo_akhir;
                    
                    }
                    else if($p->parent_name == "Pendapatan Lainnya"){
                        $array_pendapatan_lainnya[$i]['class'] = $c->classification_name;
                        $array_pendapatan_lainnya[$i]['nama'][] = $a->account_name;
                        $array_pendapatan_lainnya[$i]['kode'][] = $a->account_code;
                        $array_pendapatan_lainnya[$i]['saldo_akhir'][] = $saldo_akhir;
                        $array_pendapatan_lainnya[$i]['sum'] = $pendapatan + $saldo_akhir;
                    }
                    else if($p->parent_name == "Biaya Lainnya"){
                        $array_biaya_lainnya[$i]['class'] = $c->classification_name;
                        $array_biaya_lainnya[$i]['nama'][] = $a->account_name;
                        $array_biaya_lainnya[$i]['kode'][] = $a->account_code;
                        $array_biaya_lainnya[$i]['saldo_akhir'][] = $saldo_akhir;
                        $array_biaya_lainnya[$i]['sum'] = $biaya + $saldo_akhir;
                    }

                }
                $i++;
            }
        }
        // dd($array, $bebanArray, $array_pendapatan_lainnya, $array_biaya_lainnya);
        
        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->selectRaw('YEAR(date) as year')
        ->orderBy('date', 'desc')
        ->distinct()
        ->get();
        
        return view('user.laporanLabaRugi', compact('array', 'business', 'bebanArray', 'years', 'year', 'session', 'array_pendapatan_lainnya', 'array_biaya_lainnya'));
    }

    public function changeInEquity()
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

        $parent = AccountParent::with('classification.account')
        ->where('id_business', $session)->get();

        $sum_pendapatan = 0;
        $biaya = 0;

        foreach($parent as $p){
            $i = 0;
            foreach($p->classification as $c){
                foreach($c->account as $a){
                    $position = $a->position;

                    if(!$a->initialBalance()->whereYear('date', $year)->first()){
                        $saldo_awal = 0;
                    } else {
                        $saldo_awal = $a->initialBalance()->whereYear('date', $year)->first()->amount;
                    }

                    if($a->journal()->exists()){
                        $saldo_akhir = $saldo_awal;
                        $jurnals = $a->journal()->whereHas('detail', function($q) use($year){
                            $q->whereYear('date', $year);
                        })->get();
                        foreach($jurnals as $jurnal){
                            if ($jurnal->position == $position) {
                                $saldo_akhir += $jurnal->amount;
                            }else {
                                $saldo_akhir -= $jurnal->amount;
                            }
                        }
                    } else {
                        if($a->initialBalance()->whereYear('date', $year)->first()){
                            $saldo_akhir = $saldo_awal;
                            
                        } else {
                            $saldo_akhir = 0;
                        }
                    }
                    if($p->parent_name == "Pendapatan"){
                        $sum_pendapatan += $saldo_akhir;
                    } 
                    else if($p->parent_name == "Beban"){
                        $biaya += $saldo_akhir;
                    
                    }
                    else if($p->parent_name == "Pendapatan Lainnya"){
                        $sum_pendapatan += $saldo_akhir;
                    }
                    else if($p->parent_name == "Biaya Lainnya"){
                        $biaya += $saldo_akhir;
                    }

                }
                $i++;
            }
        }
        
        $saldo_berjalan = $sum_pendapatan - $biaya;

        $ekuitas = AccountParent::with('classification.account')
        ->where('parent_name', 'Ekuitas')
        ->where('id_business', $session)
        ->first();

        $array = array();

        $classificationEkuitas = $ekuitas->classification()->get();
        foreach($classificationEkuitas as $class){
            $account = $class->account()->get();
            $l = 0;
            foreach ($account as $a){
                $position = $a->position;
                
                if(!$a->initialBalance()->whereYear('date', $year)->first()){
                    $saldo_awal = 0;
                } else {
                    $saldo_awal = $a->initialBalance()->whereYear('date', $year)->first()->amount;
                }

                if($a->journal()->exists()){
                    $saldo_akhir = $saldo_awal;
                    $jurnals = $a->journal()->whereHas('detail', function($q) use($year){
                        $q->whereYear('date', $year);
                    })->get();
        
                    foreach($jurnals as $j){
                        if($j->position == $position){
                            $saldo_akhir += $j->amount;
                        } else {
                            $saldo_akhir -= $j->amount;
                        }
                    }
                } else {
                    if($saldo_awal == 0){
                        $saldo_akhir = 0;
                    } else {
                        $saldo_akhir = $a->initialBalance()->whereYear('date', $year)->first()->amount;
                    }
                }
                $l++;

                $array[$l]['nama'] = $a->account_name;
                $array[$l]['kode'] = $a->account_code;
                $array[$l]['saldo_akhir'] = $saldo_akhir;

            }            
        }

        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->selectRaw('YEAR(date) as year')
        ->orderBy('date', 'desc')
        ->distinct()
        ->get();
        
        
        return view('user.perubahanEkuitas', compact('session', 'business', 'array', 'saldo_berjalan', 'years', 'year'));
        
    }

    public function balanceSheet()
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

        $parent = AccountParent::with('classification.account')
        ->where('id_business', $session)->get();

        $array_asset = array();
        $array_liability = array();
        $array_equity = array();

        foreach($parent as $p){
            $i = 0;
            $classification = $p->classification()->get();
            foreach($classification as $c){
                $account = $c->account()->get();
                foreach($account as $a){
                    $position = $a->position;

                    if(!$a->initialBalance()->whereYear('date', $year)->first()){
                        $saldo_awal = 0;
                    } else {
                        $saldo_awal = $a->initialBalance()->whereYear('date', $year)->first()->amount;
                    }

                    if($a->journal()->exists()){
                        $saldo_akhir = $saldo_awal;
                        $jurnals = $a->journal()->whereHas('detail', function($q) use($year){
                            $q->whereYear('date', $year);
                        })->get();
                        foreach($jurnals as $jurnal){
                            if ($jurnal->position == $position) {
                                $saldo_akhir += $jurnal->amount;
                            }else {
                                $saldo_akhir -= $jurnal->amount;
                            }
                        }
                    } else {
                        if($a->initialBalance()->whereYear('date', $year)->first()){
                            $saldo_akhir = $saldo_awal;
                            
                        } else {
                            $saldo_akhir = 0;
                        }
                    }
                    
                    if($p->parent_name == "Asset"){
                        $array_asset[$i]['class'] = $c->classification_name;
                        $array_asset[$i]['nama'][] = $a->account_name;
                        $array_asset[$i]['kode'][] = $a->account_code;
                        $array_asset[$i]['saldo_akhir'][] = $saldo_akhir;
                        
                    } 
                    else if($p->parent_name == "Liabilitas"){
                        $array_liability[$i]['class'] = $c->classification_name;
                        $array_liability[$i]['nama'][] = $a->account_name;
                        $array_liability[$i]['kode'][] = $a->account_code;
                        $array_liability[$i]['saldo_akhir'][] = $saldo_akhir;
                    
                    }
                    else if($p->parent_name == "Ekuitas"){
                        $array_equity[$i]['class'] = $c->classification_name;
                        $array_equity[$i]['nama'][] = $a->account_name;
                        $array_equity[$i]['kode'][] = $a->account_code;
                        $array_equity[$i]['saldo_akhir'][] = $saldo_akhir;
                    
                    }
                }
                $i++;
            }
        }

        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->selectRaw('YEAR(date) as year')
        ->orderBy('date', 'desc')
        ->distinct()
        ->get();

        return view('user.neraca', compact('array_asset', 'array_equity', 'array_liability', 'years', 'year', 'session', 'business'));
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
