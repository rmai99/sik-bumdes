<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\Collection;
use Auth;
use App\BusinessSession;
use App\Business;
use App\Companies;
use App\Employee;
use App\Account;
use App\AccountParent;
use App\DetailJournal;
use App\InitialBalance;
use DB;
use PDF;
use Illuminate\Support\Facades\Storage;

class TrialBalanceController extends Controller
{

    public $successStatus = 200;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
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
      
      $parents = AccountParent::with('classification.parent')->where('id_business', $session->id)->get();
      $i = 0;
      $jumlah_debit = 0;
      $jumlah_kredit = 0;
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
                  $arr = array();
                  $arr['account_id'] = $a->id;
                  $arr['account_name'] = $a->account_name;
                  $arr['account_code'] = $a->account_code;
                  $arr['position'] = $a->position;

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
                          // $q->whereIn(DB::RAW('month(date)'), $month);
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
                  if ($position == "Debit") {
                    $jumlah_debit += (int)$ending_balance;
                  }else if($position == "Kredit"){
                    $jumlah_kredit += (int)$ending_balance;
                  }
                  $arr['saldo_akhir'] = $ending_balance;
                  // $balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'] = $ending_balance;
                  if ($ending_balance != "0") {
                    $balance[$i]['classification'][$j]['account'][$k] = $arr;
                    $k++;
                  }
              }
              $j++;
          }
          $i++;
      }
      
      $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
        $q->where('id_business', $session->id);
      })->selectRaw('YEAR(date) as year')
      ->orderBy('date', 'desc')->distinct()->get();
      $total = array();
      $total['debit'] = $jumlah_debit;
      $total['kredit'] = $jumlah_kredit;
      
      $array = array();
      $array['available_year'] = $years->pluck('year');
      $array['total'] = $total;
      $array['data'] = $balance;

      return new Collection($array);
    }

    public function export()
    {
        // dd($year);
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
        //Menghitung saldo akun
        $parents = AccountParent::with('classification.parent')->where('id_business', $session->id)->get();
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
                            // $q->whereIn(DB::RAW('month(date)'), $month);
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

        $company = Companies::where('id_user', $user->id)->first();
        if (!$company) {
            $employee = Employee::where('id_user', $user->id)->first();
            $company = Companies::where('id', $employee->id_company)->first();
          }
        
        $dateObj   = \DateTime::createFromFormat('!m', $month);
        $monthName = $dateObj->format('F');
        $file = 'neraca-saldo-periode-'.strftime("%B", strtotime($monthName)). '-' . $year .'-'. time();
        $pdf = PDF::loadView('user.neracaSaldoExport', compact('balance', 'company', 'year', 'month'))->save('storage/neraca-saldo/'.$file.'.pdf');
        $url = asset('storage/neraca-saldo/'.$file.'.pdf');
        
        $data['url'] = $url;
        return new Collection($data);
    }

    public function search(Request $request)
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

      $parents = AccountParent::with('classification.parent')
      ->where('id_business', $session->id)->get();
      $i = 0;
      $jumlah_debit = 0;
      $jumlah_kredit = 0;
      foreach($parents as $p){
          $balance[$i]['parent_code'] = $p->parent_code;
          $balance[$i]['parent_name'] = $p->parent_name;
          $classification = $p->classification()->get();
          $j = 0;
          foreach($classification as $c){
              $balance[$i]['classification'][$j]['classification_id'] = $c->id;
              $balance[$i]['classification'][$j]['classification_name'] = $c->classification_name;
              $account = $c->account()->with('initialBalance', 'journal')
              ->where('account_name','like','%'.$keyword.'%')->get();
              $k = 0;
              foreach($account as $a){
                  $arr = array();
                  $arr['account_id'] = $a->id;
                  $arr['account_name'] = $a->account_name;
                  $arr['account_code'] = $a->account_code;
                  $arr['position'] = $a->position;

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
                          // $q->whereIn(DB::RAW('month(date)'), $month);
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
                  if ($position == "Debit") {
                    $jumlah_debit += (int)$ending_balance;
                  }else if($position == "Kredit"){
                    $jumlah_kredit += (int)$ending_balance;
                  }
                  $arr['saldo_akhir'] = $ending_balance;
                  // $balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'] = $ending_balance;
                  if ($ending_balance != "0") {
                    $balance[$i]['classification'][$j]['account'][$k] = $arr;
                    $k++;
                  }
              }
              $j++;
          }
          $i++;
      }
      
      $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
        $q->where('id_business', $session->id);
      })->selectRaw('YEAR(date) as year')
      ->orderBy('date', 'desc')->distinct()->get();
      $total = array();
      $total['debit'] = $jumlah_debit;
      $total['kredit'] = $jumlah_kredit;
      
      $array = array();
      $array['available_year'] = $years->pluck('year');
      $array['total'] = $total;
      $array['data'] = $balance;

      return new Collection($array);
    }

}
