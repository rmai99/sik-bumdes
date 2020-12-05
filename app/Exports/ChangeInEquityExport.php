<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromView;
use Auth;
use App\User;
use App\Companies;
use App\Business;
use App\Account;
use App\InitialBalance;
use App\AccountParent;
use App\Employee;
use App\DetailJournal;

class ChangeInEquityExport implements FromView, WithColumnFormatting, WithEvents
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
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
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
        foreach($parent as $p){
            foreach($p->classification as $c){
                $i = 0;
                foreach($c->account as $a){
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
                    $i++;
                    if($p->parent_name == "Ekuitas"){
                        $equityArray[$i]['name'] = $a->account_name;
                        $equityArray[$i]['code'] = $a->account_code;
                        $equityArray[$i]['ending balance'] = $endingBalance;
                    } 
                    if($p->parent_name == "Pendapatan"){
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
            }
        }
        return view('user.exports.perubahanEkuitasExport', compact('session', 'business', 'equityArray', 'saldo_berjalan', 'profil', 'business_profile'));
    }
}
