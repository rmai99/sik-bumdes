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
use DB;
use PDF;

class FinancialReportController extends Controller
{
    
    public function __construct()
    {
        $this->middleware(['role:company|employee']);

        $this->middleware('auth');
        
    }
    public function incomeStatement()
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

        if (isset($_GET['year'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }
        
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
        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();

        // ddd($parent);
        return view('user.laporanLabaRugi', compact('incomeArray', 'business', 'expenseArray', 'years', 'year', 'session', 'othersIncomeArray', 'othersExpenseArray', 'income', 'expense','getBusiness', 'othersIncome', 'othersExpense'));
    }

    public function incomeStatementExport($year, $month = null)
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

        if ($month == null) {
            $month = date('m');
        }

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
        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();

        $company = Companies::where('id_user', $user)->first();
        $pdf = PDF::loadView('user.laporanLabaRugiExport', compact('incomeArray', 'expenseArray', 'years', 'year', 'othersIncomeArray', 'othersExpenseArray', 'income', 'expense','company', 'othersIncome', 'othersExpense'));
        return $pdf->stream();
        
        return view('user.laporanLabaRugiExport', compact('incomeArray', 'business', 'expenseArray', 'years', 'year', 'session', 'othersIncomeArray', 'othersExpenseArray', 'income', 'expense','getBusiness', 'othersIncome', 'othersExpense'));
    }

    public function changeInEquity()
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

        if (isset($_GET['year'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }

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
        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();
        return view('user.perubahanEkuitas', compact('session', 'business', 'equityArray', 'saldo_berjalan', 'years', 'year', 'getBusiness'));
    }

    public function changeInEquityExport($year, $month = null)
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

        if ($month == null) {
            $month = date('m');
        }

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

        $company = Companies::where('id_user', $user)->first();
        $pdf = PDF::loadView('user.perubahanEkuitasExport', compact('equityArray', 'saldo_berjalan', 'company'));
        return $pdf->stream();
        
        // return view('user.perubahanEkuitasExport',  compact('equityArray', 'saldo_berjalan', 'years', 'year', 'month', 'company'));
        // return view('user.perubahanEkuitas', compact('session', 'business', 'equityArray', 'saldo_berjalan', 'years', 'year', 'getBusiness'));
    }

    public function balanceSheet()
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

        if (isset($_GET['year'], $_GET['month'])) {
            $year = $_GET['year'];  
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }
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
            $equitas = $modal_awal - $saldo_berjalan + $prive;
        }
        // dd($assetArray, $equityArray, $liabilityArray, $equitas);

        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();
        return view('user.neraca', compact('assetArray', 'equityArray', 'liabilityArray', 'years', 'year', 'session', 'business', 'getBusiness', 'equitas'));
    }

    public function balanceSheetExport($year, $month = null)
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
            $company = Companies::where('id_user', $user)->first();
        } else {
            $getBusiness = Employee::with('business')->where('id_user', $user)->first();
            $session = $getBusiness->id_business;
        }

        if ($month == null) {
            $month = date('m');
        }
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
            $equitas = $modal_awal - $saldo_berjalan + $prive;
        }

        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();

        $pdf = PDF::loadView('user.neracaExport', compact('assetArray', 'equityArray', 'liabilityArray', 'years', 'year', 'company', 'equitas'));
        return $pdf->stream();
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
