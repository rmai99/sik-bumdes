<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
use App\Companies;
use App\Business;
use App\AccountParent;

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

        $session = session('business');

        $company = Companies::where('id_user', $user)->first()->id;

        $business = Business::where('id_company', $company)->get();
        $getBusiness = Business::where('id_company', $company)->first()->id;

        if($session == 0){
            $session = $getBusiness;
        }
        
        //TOTAL PENDAPATAN
        $pendapatan = AccountParent::with('classification.account')
        ->where('parent_name', 'Pendapatan')
        ->where('id_business', $session)->first();

        $array = array();
        $y = 0;
        $class = $pendapatan->classification()->get();

        foreach($class as $classification){
            $array[$y]['sum'] = 0;
            $account = $classification->account()->get();

            $array[$y]['class'] = $classification->classification_name;
            $i = 0;
            
            foreach ($account as $a){
                $i++;
                if(!$a->initialBalance()->whereYear('date', $year)->first()){
                    $saldo_awal =0;
                } else {
                    $saldo_awal = $a->initialBalance()->whereYear('date', $year)->first()->amount;
                }
                
                $array[$y]['nama'][] = $a->account_name;
                $array[$y]['kode'][] = $a->account_code;
                
                $position = $a->position;
                if($a->journal()->exists()){
                    $saldo_akhir = $saldo_awal;
                    $jurnals = $a->journal()->whereYear('date', $year)->get();
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

                $array[$y]['saldo_akhir'][] = $saldo_akhir;
                $array[$y]['sum'] += $saldo_akhir;
            }
            $y++;
        }

        //TOTAL BIAYA
        $beban = AccountParent::with('classification.account')
        ->where('parent_name', 'Beban')
        ->where('id_business', $session)->first();

        $bebanArray = array();
        $x = 0;
        $getClassification = $beban->classification()->get();

        foreach($getClassification as $classificationCost){
            $bebanArray[$x]['sum-biaya'] = 0;
            $accountCost = $classificationCost->account()->get();
            
            $bebanArray[$x]['classification'] = $classificationCost->classification_name;
            
            $i = 0;
            foreach($accountCost as $a){
                $i++;
                if(!$a->initialBalance()->whereYear('date', $year)->first()){
                    $saldo_biaya = 0;
                }else{
                    $saldo_biaya = $a->initialBalance()->whereYear('date', $year)->first()->amount;
                }
                
                $position = $a->position;
                if($a->journal()->exists()){
                    $saldo_akhir_biaya = $saldo_biaya;
                    $jurnals = $a->journal()->whereYear('date', $year)->get();
                    foreach($jurnals as $jurnal){
                        if ($jurnal->position == $position) {
                            $saldo_akhir_biaya += $jurnal->amount;
                        }else {
                            $saldo_akhir_biaya -= $jurnal->amount;
                        }
                    }
                } else {
                    if($a->initialBalance()->whereYear('date', $year)->first()){
                        $saldo_akhir_biaya = $saldo_biaya;
                    } else {
                        $saldo_akhir_biaya = 0;
                    }
                }
                $bebanArray[$x]['nama-akun'][] = $a->account_name;
                $bebanArray[$x]['kode-akun'][] = $a->account_code;
                $bebanArray[$x]['saldo_akhir_biaya'][] = $saldo_akhir_biaya;
                $bebanArray[$x]['sum-biaya'] += $saldo_akhir_biaya;
            }
            
            $x++;
        }

        return view('user.laporanLabaRugi', compact('array', 'bisnis', 'bebanArray'));
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

        $session = session('bisnis');

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
            
        }
        $modalDisetor = $classificationEkuitas->account()->where('account_name', 'like', 'Modal Disetor%')->first();
        
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

        return view('/laporanPerubahanEkuitas', compact('bisnis', 'saldo_akhir'));
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

        // dd($array, $bebanArray);

