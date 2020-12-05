<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidth;
use App\User;
use Auth;
use App\Companies;
use App\Business;
use App\Account;
use App\InitialBalance;
use App\AccountParent;
use App\Employee;
use App\DetailJournal;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TrialBalanceExport implements FromView, WithColumnFormatting, WithEvents
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
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
        
    }

    public function registerEvents(): array
    {
        $styleArray = [
            'font' => [
                'bold' => true,
                ]
            ];

        $borderArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        return [
            AfterSheet::class => function(AfterSheet $event) use ($styleArray, $borderArray){
                $event->sheet->getStyle('A5:F5')->applyFromArray($styleArray);
                // $event->sheet->getStyle('A5:F37')->applyFromArray($borderArray);
                $event->sheet->insertNewColumnBefore('A', 1);
            },
        ];
    }

    public function view(): View
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
            ->where('id_company', $company)
            ->where('id', $session)->first();
        } else {
            $getBusiness = Employee::with('business')->where('id_user', $user->id)->first();
            $session = $getBusiness->id_business;
        }
        $profil = Companies::where('id_user', $user->id)->first();
        $business_profile = $getBusiness->business_name;

        $year = $this->year;
        $month = $this->month;
        //Menghitung saldo akun
        $parents = AccountParent::with('classification.parent')->where('id_business', $session)->get();
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
                        $journals = $a->journal()->whereHas('detail', function($q) use($year, $month){
                            $q->whereYear('date', $year);
                            $q->whereMonth('date', '>=', '01');
                            $q->whereMonth('date', '<=', $month);
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

        return view('user.exports.neracaSaldoExcelExport', compact('balance', 'profil', 'business_profile'));
    }
}