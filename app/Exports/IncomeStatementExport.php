<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Auth;
use App\Companies;
use App\Business;
use App\AccountParent;
use App\InitialBalance;
use App\Employee;
use DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;

class IncomeStatementExport implements FromView, WithColumnFormatting, WithEvents
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
            ->where('id_company', $company)->where('id', $session)->first();
        } else {
            $getBusiness = Employee::with('business')->where('id_user', $user)->first();
            $session = $getBusiness->id_business;
        }

        $year = $this->year;
        $month = $this->month;
        $profil = Companies::where('id_user', $user)->first();
        $business_profile = $getBusiness->business_name;

        $parent = AccountParent::with('classification.account')->where('id_business', $session)->get();

        $othersExpense = 0;
        $othersIncome = 0;
        $income = 0;
        $expense = 0;
        foreach($parent as $p){
            $i = 0;
            $classification = $p->classification()->get();
            foreach($classification as $c){
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
                    
                    if($p->parent_name == "Pendapatan"){
                        $incomeArray[$i]['classification'] = $c->classification_name;
                        $incomeArray[$i]['name'][] = $a->account_name;
                        $incomeArray[$i]['code'][] = $a->account_code;
                        $incomeArray[$i]['ending balance'][] = $endingBalance;
                        if($position == "Kredit"){
                            $income += $endingBalance;
                        } else {
                            $income -= $endingBalance;
                        }
                    } 
                    else if($p->parent_name == "Beban"){
                        $expenseArray[$i]['classification'] = $c->classification_name;
                        $expenseArray[$i]['name'][] = $a->account_name;
                        $expenseArray[$i]['code'][] = $a->account_code;
                        $expenseArray[$i]['ending balance'][] = $endingBalance;
                        if($position == "Debit"){
                            $expense += $endingBalance;
                        } else {
                            $expense -= $endingBalance;
                        }
                    }
                    else if($p->parent_name == "Pendapatan Lainnya"){
                        $othersIncomeArray[$i]['classification'] = $c->classification_name;
                        $othersIncomeArray[$i]['name'][] = $a->account_name;
                        $othersIncomeArray[$i]['code'][] = $a->account_code;
                        $othersIncomeArray[$i]['ending balance'][] = $endingBalance;
                        if($position == "Kredit"){
                            $othersIncome += $endingBalance;
                        } else {
                            $othersIncome -= $endingBalance;
                        }
                    }
                    else if($p->parent_name == "Biaya Lainnya"){
                        $othersExpenseArray[$i]['classification'] = $c->classification_name;
                        $othersExpenseArray[$i]['name'][] = $a->account_name;
                        $othersExpenseArray[$i]['code'][] = $a->account_code;
                        $othersExpenseArray[$i]['ending balance'][] = $endingBalance;
                        if($position == "Debit"){
                            $othersExpense += $endingBalance;
                        } else {
                            $othersExpense -= $endingBalance;
                        }
                    }
                }
                $i++;
            }
        }
        
        // ddd($parent);
        return view('user.exports.labaRugiExport', compact('incomeArray', 'business',
                    'expenseArray', 'othersIncomeArray', 'othersExpenseArray', 'income',
                    'expense','getBusiness', 'othersIncome', 'othersExpense', 'profil', 'business_profile', 'year'));
    }
}
