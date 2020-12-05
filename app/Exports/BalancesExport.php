<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use Auth;
use App\Companies;
use App\Business;
use App\AccountParent;
use App\InitialBalance;
use App\Employee;

class BalancesExport implements FromView, WithColumnFormatting, WithEvents
{
    protected $year;
    protected $month;
    
    function __construct($year, $month) {
        $this->year = $year;
        $this->month = $month;
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                $event->sheet->insertNewColumnBefore('A', 1);
            },
        ];
    }

    public function view(): View
    {
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

        $year = $this->year;
        $month = $this->month;
        $profil = Companies::where('id_user', $user)->first();
        $business_profile = $getBusiness->business_name;

        $parent = AccountParent::with('classification.account')->where('id_business', $session)->get();
        $saldo_berjalan = 0;
        $prive = 0;
        foreach($parent as $p){
            $i = 0;
            $classification = $p->classification()->get();
            foreach($classification as $c){
                $sum=0;
                $account = $c->account()->get();
                foreach($account as $a){
                    $position = $a->position;
                    if(!$a->initialBalance()->whereYear('date', $year)->first()){
                        $beginningBalance = 0;
                    } else {
                        $beginningBalance = $a->initialBalance()->whereYear('date', $year)->first()->amount;
                    }
                    if($a->journal()->exists()){
                        $endingBalance = $beginningBalance;
                        $jurnals = $a->journal()->whereHas('detail', function($q) use($year, $month){
                            $q->whereYear('date', $year);
                            $q->whereMonth('date', '>=', '01');
                            $q->whereMonth('date', '<=', $month);
                        })->get();
                        foreach($jurnals as $jurnal){
                            if ($jurnal->position == $position) {
                                $endingBalance += $jurnal->amount;
                            }else {
                                $endingBalance -= $jurnal->amount;
                            }
                        }
                    } else {
                        if($a->initialBalance()->whereYear('date', $year)->first()){
                            $endingBalance = $beginningBalance;
                        } else {
                            $endingBalance = 0;
                        }
                    }
                    if($p->parent_name == "Asset"){
                        if($position == "Debit"){
                            $sum += $endingBalance;
                        } else {
                            $sum -= $endingBalance;
                        }
                        $assetArray[$i]['classification'] = $c->classification_name;
                        $assetArray[$i]['name'][] = $a->account_name;
                        $assetArray[$i]['code'][] = $a->account_code;
                        $assetArray[$i]['ending balance'][] = $endingBalance;
                        $assetArray[$i]['sum'] = $sum;
                    }
                    else if($p->parent_name == "Liabilitas"){
                        if($position == "Kredit"){
                            $sum += $endingBalance;
                        } else {
                            $sum -= $endingBalance;
                        }
                        $liabilityArray[$i]['classification'] = $c->classification_name;
                        $liabilityArray[$i]['name'][] = $a->account_name;
                        $liabilityArray[$i]['code'][] = $a->account_code;
                        $liabilityArray[$i]['ending balance'][] = $endingBalance;
                        $liabilityArray[$i]['sum'] = $sum;
                    } 
                    else if ($p->parent_name == "Ekuitas"){
                        if($a->account_name == "Modal Disetor"){
                            $modal_awal = $endingBalance;
                        }
                        if($a->account_name == "Prive"){
                            $prive = $endingBalance;
                        }
                        $equityArray[$i]['classification'] = $c->classification_name;
                        $equityArray[$i]['name'][] = $a->account_name;
                        $equityArray[$i]['code'][] = $a->account_code;
                        $equityArray[$i]['ending balance'][] = $endingBalance;
                    }
                    else if($p->parent_name == "Pendapatan"){
                        if($position == "Kredit"){
                            $saldo_berjalan += $endingBalance;
                        } else {
                            $saldo_berjalan -= $endingBalance;
                        }
                    } 
                    else if($p->parent_name == "Beban"){
                        if($position == "Debit"){
                            $saldo_berjalan -= $endingBalance;
                        } else {
                            $saldo_berjalan += $endingBalance;
                        }
                    }
                    else if($p->parent_name == "Pendapatan Lainnya"){
                        if($position == "Kredit"){
                            $saldo_berjalan += $endingBalance;
                        } else {
                            $saldo_berjalan -= $endingBalance;
                        }
                    }
                    else if($p->parent_name == "Biaya Lainnya"){
                        if($position == "Debit"){
                            $saldo_berjalan -= $endingBalance;
                        } else {
                            $saldo_berjalan += $endingBalance;
                        }
                    }
                }
                $i++;
            }
        }
        if($saldo_berjalan >= 0){
            $equitas = $modal_awal + $saldo_berjalan - $prive;
        } else {
            $equitas = $modal_awal + $saldo_berjalan + $prive;
        }
        
        return view('user.exports.neracaExport', compact('assetArray', 'equityArray', 'liabilityArray', 'session', 'business', 'equitas', 'profil', 'business_profile'));
    }
}