<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\Collection;
use App\BusinessSession;
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

    public $successStatus = 200;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function incomeStatement()
    {
        $user = Auth::user();
        $session = BusinessSession::where('id_user', $user->id)->with('business')->first();
        if (!$session) {
          $employee = Employee::where('id_user', $user->id)->first();
          $company = Companies::where('id', $employee->id_company)->first();
          $session = BusinessSession::where('id_user', $company->id_user)->with('business')->first();
        }
        if(!$session->business){
          return response()->json(['success'=>false,'error'=>'Sesi bisnis belum dipilih.'], 400);
        }
        $session = $session->business;

        if (isset($_GET['year'], $_GET['month'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }
        
        $parent = AccountParent::with('classification.account')->where('id_business', $session->id)->get();

        $othersExpense = 0;
        $othersIncome = 0;
        $income = 0;
        $expense = 0;
        $incomeArray = array();
        $expenseArray = array();
        $othersIncomeArray = array();
        $othersExpenseArray = array();
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

                    if ($endingBalance != 0) {
                      
                      if($p->parent_name == "Pendapatan"){
                          $incomeArray[$i]['classification'] = $c->classification_name;
                          $incomeArray[$i]['name'][] = $a->account_name;
                          $incomeArray[$i]['code'][] = $a->account_code;
                          $incomeArray[$i]['ending_balance'][] = $endingBalance;
                          if($position == "Kredit"){
                              $income += $endingBalance;
                          } else {
                              $income -= $endingBalance;
                          }
                          $incomeArray[$i]['total'] = $income;
                      } 
                      else if($p->parent_name == "Beban"){
                          $expenseArray[$i]['classification'] = $c->classification_name;
                          $expenseArray[$i]['name'][] = $a->account_name;
                          $expenseArray[$i]['code'][] = $a->account_code;
                          $expenseArray[$i]['ending_balance'][] = $endingBalance;
                          if($position == "Debit"){
                              $expense += $endingBalance;
                          } else {
                              $expense -= $endingBalance;
                          }
                          $expenseArray[$i]['total'] = $expense;
                      }
                      else if($p->parent_name == "Pendapatan Lainnya"){
                          $othersIncomeArray[$i]['classification'] = $c->classification_name;
                          $othersIncomeArray[$i]['name'][] = $a->account_name;
                          $othersIncomeArray[$i]['code'][] = $a->account_code;
                          $othersIncomeArray[$i]['ending_balance'][] = $endingBalance;
                          if($position == "Kredit"){
                              $othersIncome += $endingBalance;
                          } else {
                              $othersIncome -= $endingBalance;
                          }
                          $othersIncomeArray[$i]['total'] = $othersIncome;
                      }
                      else if($p->parent_name == "Biaya Lainnya"){
                          $othersExpenseArray[$i]['classification'] = $c->classification_name;
                          $othersExpenseArray[$i]['name'][] = $a->account_name;
                          $othersExpenseArray[$i]['code'][] = $a->account_code;
                          $othersExpenseArray[$i]['ending_balance'][] = $endingBalance;
                          if($position == "Debit"){
                              $othersExpense += $endingBalance;
                          } else {
                              $othersExpense -= $endingBalance;
                          }
                          $othersExpenseArray[$i]['total'] = $othersExpense;
                      }
                    }
                }
                $i++;
            }
        }
        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session->id);
        })->selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();

        $array = array();
        $array['available_year'] = $years->pluck('year');
        $data = array();
        $data['income'] = $incomeArray;
        $data['expense'] = $expenseArray;
        $data['other_income'] = $othersIncomeArray;
        $data['other_expense'] = $othersExpenseArray;
        $data['laba_usaha'] = $income - $expense;
        $data['laba_berjalan'] = $income + $othersIncome - $expense - $othersExpense;
        $array['laba_rugi'] = $data;

        return new Collection($array);
    }
    
    
    public function incomeStatementDashboard()
    {
        $user = Auth::user();
        $session = BusinessSession::where('id_user', $user->id)->with('business')->first();
        if (!$session) {
          $employee = Employee::where('id_user', $user->id)->first();
          $company = Companies::where('id', $employee->id_company)->first();
          $session = BusinessSession::where('id_user', $company->id_user)->with('business')->first();
        }
        if(!$session->business){
          return response()->json(['success'=>false,'error'=>'Sesi bisnis belum dipilih.'], 400);
        }
        $session = $session->business;
        
        $weekIterator = 1;
        if(isset($_GET["os_weekly"]) && $_GET["is_weekly"] == true){
            $weekIterator = 5;
        }

        $parent = AccountParent::with('classification.account')->where('id_business', $session->id)->get();

        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session->id);
        })->selectRaw('YEAR(date) as year')->distinct()->get();
        $years = $years->pluck('year');

        $j = 0;
        $allData = array();
        foreach($years as $year){
            for($month = 1; $month<13; $month++){
                for($week = 1; $week<=$weekIterator; $week++){
                    $laba_berjalan = 0;
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
                                    $jurnals = $a->journal()->whereHas('detail', function($q) use($year, $month, $week){
                                        $q->whereYear('date', $year);
                                        $q->whereMonth('date', '>=', '01');
                                        $q->whereMonth('date', '<=', $month);
                                        if(isset($_GET["weekly"])){
                                            $q->whereDay('date', '>=', "01");
                                            $q->whereDay('date', '<=', $week*7);
                                        }
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
            
                                if ($endingBalance != 0) {
                                
                                if($p->parent_name == "Pendapatan"){
                                    $incomeArray[$i]['classification'] = $c->classification_name;
                                    $incomeArray[$i]['name'][] = $a->account_name;
                                    $incomeArray[$i]['code'][] = $a->account_code;
                                    $incomeArray[$i]['ending_balance'][] = $endingBalance;
                                    if($position == "Kredit"){
                                        $income += $endingBalance;
                                    } else {
                                        $income -= $endingBalance;
                                    }
                                    $incomeArray[$i]['total'] = $income;
                                } 
                                else if($p->parent_name == "Beban"){
                                    $expenseArray[$i]['classification'] = $c->classification_name;
                                    $expenseArray[$i]['name'][] = $a->account_name;
                                    $expenseArray[$i]['code'][] = $a->account_code;
                                    $expenseArray[$i]['ending_balance'][] = $endingBalance;
                                    if($position == "Debit"){
                                        $expense += $endingBalance;
                                    } else {
                                        $expense -= $endingBalance;
                                    }
                                    $expenseArray[$i]['total'] = $expense;
                                }
                                else if($p->parent_name == "Pendapatan Lainnya"){
                                    $othersIncomeArray[$i]['classification'] = $c->classification_name;
                                    $othersIncomeArray[$i]['name'][] = $a->account_name;
                                    $othersIncomeArray[$i]['code'][] = $a->account_code;
                                    $othersIncomeArray[$i]['ending_balance'][] = $endingBalance;
                                    if($position == "Kredit"){
                                        $othersIncome += $endingBalance;
                                    } else {
                                        $othersIncome -= $endingBalance;
                                    }
                                    $othersIncomeArray[$i]['total'] = $othersIncome;
                                }
                                else if($p->parent_name == "Biaya Lainnya"){
                                    $othersExpenseArray[$i]['classification'] = $c->classification_name;
                                    $othersExpenseArray[$i]['name'][] = $a->account_name;
                                    $othersExpenseArray[$i]['code'][] = $a->account_code;
                                    $othersExpenseArray[$i]['ending_balance'][] = $endingBalance;
                                    if($position == "Debit"){
                                        $othersExpense += $endingBalance;
                                    } else {
                                        $othersExpense -= $endingBalance;
                                    }
                                    $othersExpenseArray[$i]['total'] = $othersExpense;
                                }
                                }
                            }
                            $i++;
                        }
                    }
                    $laba_berjalan = $income + $othersIncome - $expense - $othersExpense;
                    
                    $data = array();
                    $data['week'] = $week;
                    $data['month'] = $month;
                    $data['year'] = $year;
                    $data['laba_usaha'] = $income - $expense;
                    $data['laba_berjalan'] = $income + $othersIncome - $expense - $othersExpense;
                    $allData[$j] = $data;
                    $j++;
                }
            }
        }
        return new Collection($allData);
    }

    
    public function incomeStatementSearch(Request $request)
    {
        $user = Auth::user();
        $session = BusinessSession::where('id_user', $user->id)->with('business')->first();
        if (!$session) {
          $employee = Employee::where('id_user', $user->id)->first();
          $company = Companies::where('id', $employee->id_company)->first();
          $session = BusinessSession::where('id_user', $company->id_user)->with('business')->first();
        }   
        if(!$session->business){
          return response()->json(['success'=>false,'error'=>'Sesi bisnis belum dipilih.'], 400);
        }
        $session = $session->business;

        if (isset($_GET['year'], $_GET['month'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }
        
        $keyword = ($request['query'] != null) ? $request['query'] : "";

        $parent = AccountParent::with('classification.account')->where('id_business', $session->id)->get();

        $othersExpense = 0;
        $othersIncome = 0;
        $income = 0;
        $expense = 0;
        $incomeArray = array();
        $expenseArray = array();
        $othersIncomeArray = array();
        $othersExpenseArray = array();
        foreach($parent as $p){
            $i = 0;
            $classification = $p->classification()->get();
            foreach($classification as $c){
                $account = $c->account()
                ->where('account_name','like','%'.$keyword.'%')->get();
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

                    if ($endingBalance != 0) {
                      
                      if($p->parent_name == "Pendapatan"){
                          $incomeArray[$i]['classification'] = $c->classification_name;
                          $incomeArray[$i]['name'][] = $a->account_name;
                          $incomeArray[$i]['code'][] = $a->account_code;
                          $incomeArray[$i]['ending_balance'][] = $endingBalance;
                          if($position == "Kredit"){
                              $income += $endingBalance;
                          } else {
                              $income -= $endingBalance;
                          }
                          $incomeArray[$i]['total'] = $income;
                      } 
                      else if($p->parent_name == "Beban"){
                          $expenseArray[$i]['classification'] = $c->classification_name;
                          $expenseArray[$i]['name'][] = $a->account_name;
                          $expenseArray[$i]['code'][] = $a->account_code;
                          $expenseArray[$i]['ending_balance'][] = $endingBalance;
                          if($position == "Debit"){
                              $expense += $endingBalance;
                          } else {
                              $expense -= $endingBalance;
                          }
                          $expenseArray[$i]['total'] = $expense;
                      }
                      else if($p->parent_name == "Pendapatan Lainnya"){
                          $othersIncomeArray[$i]['classification'] = $c->classification_name;
                          $othersIncomeArray[$i]['name'][] = $a->account_name;
                          $othersIncomeArray[$i]['code'][] = $a->account_code;
                          $othersIncomeArray[$i]['ending_balance'][] = $endingBalance;
                          if($position == "Kredit"){
                              $othersIncome += $endingBalance;
                          } else {
                              $othersIncome -= $endingBalance;
                          }
                          $othersIncomeArray[$i]['total'] = $othersIncome;
                      }
                      else if($p->parent_name == "Biaya Lainnya"){
                          $othersExpenseArray[$i]['classification'] = $c->classification_name;
                          $othersExpenseArray[$i]['name'][] = $a->account_name;
                          $othersExpenseArray[$i]['code'][] = $a->account_code;
                          $othersExpenseArray[$i]['ending_balance'][] = $endingBalance;
                          if($position == "Debit"){
                              $othersExpense += $endingBalance;
                          } else {
                              $othersExpense -= $endingBalance;
                          }
                          $othersExpenseArray[$i]['total'] = $othersExpense;
                      }
                    }
                }
                $i++;
            }
        }
        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session->id);
        })->selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();

        $array = array();
        $array['available_year'] = $years->pluck('year');
        $data = array();
        $data['income'] = $incomeArray;
        $data['expense'] = $expenseArray;
        $data['other_income'] = $othersIncomeArray;
        $data['other_expense'] = $othersExpenseArray;
        $data['laba_usaha'] = $income - $expense;
        $data['laba_berjalan'] = $income + $othersIncome - $expense - $othersExpense;
        $array['laba_rugi'] = $data;

        return new Collection($array);
    }

    public function incomeStatementExport()
    {
        $user = Auth::user();
        $session = BusinessSession::where('id_user', $user->id)->with('business')->first();
        if (!$session) {
          $employee = Employee::where('id_user', $user->id)->first();
          $company = Companies::where('id', $employee->id_company)->first();
          $session = BusinessSession::where('id_user', $company->id_user)->with('business')->first();
        }
        if(!$session->business){
          return response()->json(['success'=>false,'error'=>'Sesi bisnis belum dipilih.'], 400);
        }
        $session = $session->business;

        if (isset($_GET['year'], $_GET['month'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }

        $parent = AccountParent::with('classification.account')->where('id_business', $session->id)->get();

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
            $q->where('id_business', $session->id);
        })->selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();

        $company = Companies::where('id_user', $user->id)->first();
        if (!$company) {
            $employee = Employee::where('id_user', $user->id)->first();
            $company = Companies::where('id', $employee->id_company)->first();
        }
        
        $dateObj   = \DateTime::createFromFormat('!m', $month);
        $monthName = $dateObj->format('F');
        $file = 'laba-rugi-periode-'.strftime("%B", strtotime($monthName)). '-' . $year .'-'. time();
        $pdf = PDF::loadView('user.laporanLabaRugiExport', compact('incomeArray', 'expenseArray', 'years', 'year', 'month', 'othersIncomeArray', 'othersExpenseArray', 'income', 'expense','company', 'othersIncome', 'othersExpense'))->save('storage/laba-rugi/'.$file.'.pdf');
        $url = asset('storage/laba-rugi/'.$file.'.pdf');
        
        $data['url'] = $url;
        return new Collection($data);
    }

    public function changeInEquity()
    {
        $user = Auth::user();
        $session = BusinessSession::where('id_user', $user->id)->with('business')->first();
        if (!$session) {
          $employee = Employee::where('id_user', $user->id)->first();
          $company = Companies::where('id', $employee->id_company)->first();
          $session = BusinessSession::where('id_user', $company->id_user)->with('business')->first();
        }
        if(!$session->business){
          return response()->json(['success'=>false,'error'=>'Sesi bisnis belum dipilih.'], 400);
        }
        $session = $session->business;

        if (isset($_GET['year'], $_GET['month'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }

        $parent = AccountParent::with('classification.account')->where('id_business', $session->id)->get();
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
                        $equityArray[$i]['ending_balance'] = $endingBalance;
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
            $q->where('id_business', $session->id);
        })->selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();

        $array = array();
        $array['available_year'] = $years->pluck('year');
        $data = array();
        $data['modal_awal'] = collect($equityArray)->sum('ending_balance');
        $penambahan = array();
        $prive = 0;
        $penambahan[0]['name'] = 'Laba bersih';
        $penambahan[0]['amount'] = $saldo_berjalan;
        for ($i = 1; $i <= sizeof($equityArray); $i++){
          if ($equityArray[$i]['name'] == "Prive"){
            $penambahan[$i]['name'] = $equityArray[$i]['name'];
            $penambahan[$i]['amount'] = $equityArray[$i]['ending_balance'];
            $prive += $equityArray[$i]['ending_balance'];
          }
        }
        $data['penambahan'] = $penambahan;
        $data['total_penambahan'] = $saldo_berjalan - $prive;
        $data['modal_akhir'] = $data['modal_awal'] + $saldo_berjalan - $prive;
        $array['perubahan_ekuitas'] = $data;

        return new Collection($array);
    }
    public function changeInEquitySearch(Request $request)
    {
        $user = Auth::user();
        $session = BusinessSession::where('id_user', $user->id)->with('business')->first();
        if (!$session) {
          $employee = Employee::where('id_user', $user->id)->first();
          $company = Companies::where('id', $employee->id_company)->first();
          $session = BusinessSession::where('id_user', $company->id_user)->with('business')->first();
        }
        if(!$session->business){
          return response()->json(['success'=>false,'error'=>'Sesi bisnis belum dipilih.'], 400);
        }
        $session = $session->business;

        if (isset($_GET['year'], $_GET['month'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }

        $keyword = ($request['query'] != null) ? $request['query'] : "";

        $parent = AccountParent::with('classification.account')->where('id_business', $session->id)->get();
        $saldo_berjalan = 0;
        foreach($parent as $p){
            foreach($p->classification as $c){
                $i = 0;
                $account = $c->account()
                ->where('account_name','like','%'.$keyword.'%')->get();
                
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
                    $i++;
                    $equityArray = array();
                    if($p->parent_name == "Ekuitas"){
                        $equityArray[$i]['name'] = $a->account_name;
                        $equityArray[$i]['code'] = $a->account_code;
                        $equityArray[$i]['ending_balance'] = $endingBalance;
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
            $q->where('id_business', $session->id);
        })->selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();

        $array = array();
        $array['available_year'] = $years->pluck('year');
        $data = array();
        if(isset($equityArray))
            $data['modal_awal'] = collect($equityArray)->sum('ending_balance');
        else
            $data['modal_awal'] = 0;
        $penambahan = array();
        $prive = 0;
        $penambahan[0]['name'] = 'Laba bersih';
        $penambahan[0]['amount'] = $saldo_berjalan;
        if(isset($equityArray))
            for ($i = 1; $i <= sizeof($equityArray); $i++){
            if ($equityArray[$i]['name'] == "Prive"){
                $penambahan[$i]['name'] = $equityArray[$i]['name'];
                $penambahan[$i]['amount'] = $equityArray[$i]['ending_balance'];
                $prive += $equityArray[$i]['ending_balance'];
            }
            }
        $data['penambahan'] = $penambahan;
        $data['total_penambahan'] = $saldo_berjalan - $prive;
        $data['modal_akhir'] = $data['modal_awal'] + $saldo_berjalan - $prive;
        $array['perubahan_ekuitas'] = $data;

        return new Collection($array);
    }

    public function changeInEquityExport()
    {
        $user = Auth::user();
        $session = BusinessSession::where('id_user', $user->id)->with('business')->first();
        if (!$session) {
          $employee = Employee::where('id_user', $user->id)->first();
          $company = Companies::where('id', $employee->id_company)->first();
          $session = BusinessSession::where('id_user', $company->id_user)->with('business')->first();
        }
        if(!$session->business){
          return response()->json(['success'=>false,'error'=>'Sesi bisnis belum dipilih.'], 400);
        }
        $session = $session->business;

        if (isset($_GET['year'], $_GET['month'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }

        $parent = AccountParent::with('classification.account')->where('id_business', $session->id)->get();
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

        $company = Companies::where('id_user', $user->id)->first();
        if (!$company) {
            $employee = Employee::where('id_user', $user->id)->first();
            $company = Companies::where('id', $employee->id_company)->first();
        }
        
        $dateObj   = \DateTime::createFromFormat('!m', $month);
        $monthName = $dateObj->format('F');
        $file = 'perubahan-ekuitas-'.strftime("%B", strtotime($monthName)). '-' . $year .'-'. time();
        $pdf = PDF::loadView('user.perubahanEkuitasExport', compact('equityArray', 'saldo_berjalan', 'company', 'year', 'month'))->save('storage/perubahan-ekuitas/'.$file.'.pdf');
        $url = asset('storage/perubahan-ekuitas/'.$file.'.pdf');
        
        $data['url'] = $url;
        return new Collection($data);
    }

    public function balanceSheet()
    {
        $user = Auth::user();
        $session = BusinessSession::where('id_user', $user->id)->with('business')->first();
        if (!$session) {
          $employee = Employee::where('id_user', $user->id)->first();
          $company = Companies::where('id', $employee->id_company)->first();
          $session = BusinessSession::where('id_user', $company->id_user)->with('business')->first();
        }
        if(!$session->business){
          return response()->json(['success'=>false,'error'=>'Sesi bisnis belum dipilih.'], 400);
        }
        $session = $session->business;

        if (isset($_GET['year'], $_GET['month'])) {
            $year = $_GET['year'];  
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }
        $parent = AccountParent::with('classification.account')->where('id_business', $session->id)->get();
        $saldo_berjalan = 0;
        $sum_ekuitas = 0;
        $prive = 0;
        $assetArray = array();
        $bal = array();
        $liabilityArray = array();
        $equityArray = array();
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
                    $bal[] = $endingBalance;
                    if ($endingBalance != 0) {
                      # code...
                      if($p->parent_name == "Asset"){
                          if($position == "Debit"){
                              $sum += $endingBalance;
                          } else {
                              $sum -= $endingBalance;
                          }
                          $assetArray[$i]['classification'] = $c->classification_name;
                          $assetArray[$i]['name'][] = $a->account_name;
                          $assetArray[$i]['code'][] = $a->account_code;
                          $assetArray[$i]['ending_balance'][] = $endingBalance;
                          $assetArray[$i]['total'] = $sum;
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
                          $liabilityArray[$i]['ending_balance'][] = $endingBalance;
                          $liabilityArray[$i]['total'] = $sum;
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
                    if ($p->parent_name == "Ekuitas"){
                        if($a->account_name == "Modal Disetor"){
                            $modal_awal = $endingBalance;
                        }
                        if($a->account_name == "Prive"){
                            $prive = $endingBalance;
                        }
                        $equityArray[$i]['classification'] = $c->classification_name;
                        $equityArray[$i]['name'][] = $a->account_name;
                        $equityArray[$i]['code'][] = $a->account_code;
                        $equityArray[$i]['ending_balance'][] = $endingBalance;
                        $sum_ekuitas += $endingBalance;
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

        $equityArr = array();
        $sum_ekuitas = 0;
        $z = 0;
        for ($i = 0; $i < sizeof($equityArray); $i++){
          for ($j = 0; $j < sizeof($equityArray[$i]['ending_balance']); $j++){
            if ($equityArray[$i]['name'][$j] == "Modal Disetor") {
              $equityArr[$z]['name'][] = 'Modal Disetor';
              $equityArr[$z]['code'][] = $a->account_code;
              $equityArr[$z]['ending_balance'][] = $equitas;
              $sum_ekuitas += $equitas;
              $z++;
            }
            if ($equityArray[$i]['name'][$j] != "Modal Disetor" && $equityArray[$i]['name'][$j] != "Saldo Laba Tahun Berjalan" && $equityArray[$i]['name'][$j] != "Prive" ) {
              $equityArr[$z]['name'][] = $equityArray[$i]['name'][$j];
              $equityArr[$z]['code'][] = $equityArray[$i]['code'][$j];
              $equityArr[$z]['ending_balance'][] = $equityArray[$i]['ending_balance'][$j];
              $sum_ekuitas += $equityArray[$i]['ending_balance'][$j];
              $z++;
            }
          }
        }

        // dd($assetArray, $equityArray, $liabilityArray, $equitas);

        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session->id);
        })->selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();
        
        $array = array();
        $array['available_year'] = $years->pluck('year');
        $data = array();
        $data['total_asset'] = collect($assetArray)->sum('total');
        $data['total_liability_equity'] = $sum_ekuitas + collect($liabilityArray)->sum('total');
        $data['asset'] = $assetArray;
        $data['asset']['total_asset'] = collect($assetArray)->sum('total');
        $data['liability'] = $liabilityArray;
        $data['liability']['total_liability'] = collect($liabilityArray)->sum('total');
        $data['equity'] = $equityArr;
        $data['equity']['total_equity'] = $sum_ekuitas;
        // $data['bal'] = $bal;
        $array['neraca'] = $data;

        return new Collection($array);
        return view('user.neraca', compact('assetArray', 'equityArray', 'liabilityArray', 'years', 'year', 'session', 'business', 'getBusiness', 'equitas'));
    }

    public function balanceSheetSearch(Request $request)
    {
        $user = Auth::user();
        $session = BusinessSession::where('id_user', $user->id)->with('business')->first();
        if (!$session) {
          $employee = Employee::where('id_user', $user->id)->first();
          $company = Companies::where('id', $employee->id_company)->first();
          $session = BusinessSession::where('id_user', $company->id_user)->with('business')->first();
        }
        if(!$session->business){
          return response()->json(['success'=>false,'error'=>'Sesi bisnis belum dipilih.'], 400);
        }
        $session = $session->business;

        if (isset($_GET['year'], $_GET['month'])) {
            $year = $_GET['year'];  
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }
        $keyword = ($request['query'] != null) ? $request['query'] : "";
        $parent = AccountParent::with('classification.account')->where('id_business', $session->id)->get();
        $saldo_berjalan = 0;
        $sum_ekuitas = 0;
        $prive = 0;
        $assetArray = array();
        $bal = array();
        $liabilityArray = array();
        $equityArray = array();
        foreach($parent as $p){
            $i = 0;
            $classification = $p->classification()->get();
            foreach($classification as $c){
                $sum=0;
                $account = $c->account()
                ->where('account_name','like','%'.$keyword.'%')->get();
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
                    $bal[] = $endingBalance;
                    if ($endingBalance != 0) {
                      # code...
                      $modal_awal = 0;
                      if($p->parent_name == "Asset"){
                          if($position == "Debit"){
                              $sum += $endingBalance;
                          } else {
                              $sum -= $endingBalance;
                          }
                          $assetArray[$i]['classification'] = $c->classification_name;
                          $assetArray[$i]['name'][] = $a->account_name;
                          $assetArray[$i]['code'][] = $a->account_code;
                          $assetArray[$i]['ending_balance'][] = $endingBalance;
                          $assetArray[$i]['total'] = $sum;
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
                          $liabilityArray[$i]['ending_balance'][] = $endingBalance;
                          $liabilityArray[$i]['total'] = $sum;
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
                    if ($p->parent_name == "Ekuitas"){
                        if($a->account_name == "Modal Disetor"){
                            $modal_awal = $endingBalance;
                        }
                        if($a->account_name == "Prive"){
                            $prive = $endingBalance;
                        }
                        $equityArray[$i]['classification'] = $c->classification_name;
                        $equityArray[$i]['name'][] = $a->account_name;
                        $equityArray[$i]['code'][] = $a->account_code;
                        $equityArray[$i]['ending_balance'][] = $endingBalance;
                        $sum_ekuitas += $endingBalance;
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

        $equityArr = array();
        $sum_ekuitas = 0;
        $z = 0;
        for ($i = 0; $i < sizeof($equityArray); $i++){
          for ($j = 0; $j < sizeof($equityArray[$i]['ending_balance']); $j++){
            if ($equityArray[$i]['name'][$j] == "Modal Disetor") {
              $equityArr[$z]['name'][] = 'Modal Disetor';
              $equityArr[$z]['code'][] = $a->account_code;
              $equityArr[$z]['ending_balance'][] = $equitas;
              $sum_ekuitas += $equitas;
              $z++;
            }
            if ($equityArray[$i]['name'][$j] != "Modal Disetor" && $equityArray[$i]['name'][$j] != "Saldo Laba Tahun Berjalan" && $equityArray[$i]['name'][$j] != "Prive" ) {
              $equityArr[$z]['name'][] = $equityArray[$i]['name'][$j];
              $equityArr[$z]['code'][] = $equityArray[$i]['code'][$j];
              $equityArr[$z]['ending_balance'][] = $equityArray[$i]['ending_balance'][$j];
              $sum_ekuitas += $equityArray[$i]['ending_balance'][$j];
              $z++;
            }
          }
        }

        // dd($assetArray, $equityArray, $liabilityArray, $equitas);

        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session->id);
        })->selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();
        
        $array = array();
        $array['available_year'] = $years->pluck('year');
        $data = array();
        $data['total_asset'] = collect($assetArray)->sum('total');
        $data['total_liability_equity'] = $sum_ekuitas + collect($liabilityArray)->sum('total');
        $data['asset'] = $assetArray;
        $data['asset']['total_asset'] = collect($assetArray)->sum('total');
        $data['liability'] = $liabilityArray;
        $data['liability']['total_liability'] = collect($liabilityArray)->sum('total');
        $data['equity'] = $equityArr;
        $data['equity']['total_equity'] = $sum_ekuitas;
        // $data['bal'] = $bal;
        $array['neraca'] = $data;

        return new Collection($array);
        return view('user.neraca', compact('assetArray', 'equityArray', 'liabilityArray', 'years', 'year', 'session', 'business', 'getBusiness', 'equitas'));
    }

    public function balanceSheetExport()
    {
        $user = Auth::user();
        $session = BusinessSession::where('id_user', $user->id)->with('business')->first();
        if (!$session) {
          $employee = Employee::where('id_user', $user->id)->first();
          $company = Companies::where('id', $employee->id_company)->first();
          $session = BusinessSession::where('id_user', $company->id_user)->with('business')->first();
        }
        if(!$session->business){
          return response()->json(['success'=>false,'error'=>'Sesi bisnis belum dipilih.'], 400);
        }
        $session = $session->business;

        if (isset($_GET['year'], $_GET['month'])) {
            $year = $_GET['year'];  
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }
        $parent = AccountParent::with('classification.account')->where('id_business', $session->id)->get();
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
            $q->where('id_business', $session->id);
        })->selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();

        $company = Companies::where('id_user', $user->id)->first();
        if (!$company) {
            $employee = Employee::where('id_user', $user->id)->first();
            $company = Companies::where('id', $employee->id_company)->first();
        }

        $dateObj   = \DateTime::createFromFormat('!m', $month);
        $monthName = $dateObj->format('F');
        $file = 'neraca-periode-'.strftime("%B", strtotime($monthName)). '-' . $year .'-'. time();
        $pdf = PDF::loadView('user.neracaExport', compact('assetArray', 'equityArray', 'liabilityArray', 'years', 'year', 'month', 'company', 'equitas'))->save('storage/neraca/'.$file.'.pdf');
        $url = asset('storage/neraca/'.$file.'.pdf');
        
        $data['url'] = $url;
        return new Collection($data);
    }

}