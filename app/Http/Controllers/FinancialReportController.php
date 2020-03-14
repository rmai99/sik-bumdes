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
        
        return view('user.laporanLabaRugi', compact('array', 'business', 'bebanArray', 'years', 'year', 'session'));
    }
        // Laporan Laba Rugi adalah total pendapatan - total modal.
        // Total pendapatan adalah neraca saldo (akhir) pada seluruh akun
        // yang terdapat pada klasifikasi Pendapata. Akan tetapi pada view
        // kita perlu menampilkan anak akun dari clasifikasi tersebut dan 
        // menampilkan saldo akhir pada masing2 akun
        // begitu juga dengan total beban.
        

        // Parent Pendapatan Lainnya dan Beban Lainnya juga menggunakan
        // proses seperti yang diatas

        // Laporan Laba Rugi = Pendapatan - Beban.
    
    
    public function changeInEquity()
    {

        $year= 2020;
        $role = Auth::user();
        $isOwner = $role->hasRole('owner');
        
        $user = Auth::user()->id;

        $session = session('business');

        $company = Companies::where('id_user', $user)->first()->id;

        $bisnis = Business::where('id_company', $company)->get();
        $getBusiness = Business::where('id_company', $company)->first()->id;

        if($session == 0){
            $session = $getBusiness;
        }
        $ekuitas = AccountParent::with('classification.account')
        ->where('parent_name', 'Ekuitas')
        ->where('id_business', $session)
        ->first();

        $ekuitasArray = array();
        $i = 0;
        $classificationEkuitas = $ekuitas->classification()->get();

        foreach($classificationEkuitas as $class){
            $modalDisetor = $class->account()->where('account_name', 'like', 'Modal Disetor%')->first();
        }
        
        if(!$modalDisetor->initialBalance()->whereYear('date', $year)->first()){
            $saldo_awal = 0;
        } else {
            $saldo_awal = $modalDisetor->initialBalance()->whereYear('date', $year)->first()->amount;
        }
        $position = $modalDisetor->position;
        
        if($modalDisetor->journal()->exists()){
            $saldo_akhir = $saldo_awal;
            $jurnals = $modalDisetor->journal()->whereYear('date', $year)->get();
            $i = 0;
            foreach($jurnals as $j){
                $i++;
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
                $saldo_akhir = $modalDisetor->initialBalance()->whereYear('date', $year)->first()->amount;
            }
        }
        dd($saldo_akhir);
        return view('user.laporanPerubahanEkuitas', compact('business', 'saldo_akhir', 'session'));
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
