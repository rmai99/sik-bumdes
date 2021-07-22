<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\Collection;
use Auth;
use App\GeneralJournal;
use App\Companies;
use App\Business;
use App\DetailJournal;
use App\Account;
use App\BusinessSession;
use App\Employee;
use App\InitialBalance;

class GeneralJournalController extends Controller
{

    public $successStatus = 200;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        if(isset($_GET['year']) || isset($_GET['month']) || isset($_GET['day'])){
          if (isset($_GET['year'], $_GET['month'], $_GET['day'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
            $day = $_GET['day'];
          } elseif(isset($_GET['year'], $_GET['month'])){
            $year = $_GET['year'];
            $month = $_GET['month'];
            $day = null;
          } elseif(isset($_GET['year'])){
            $year = $_GET['year'];
            $month = null;
            $day = null;
          }
        } else {
          $year = date('Y');
          $month = null;
          $day = null;
        }
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

        if ($day && $month) {
          $data = DetailJournal::with('journal.account')
          ->whereHas('journal.account.classification.parent', function($q) use($session){
            $q->where('id_business', $session->id);
          })->whereYear('date', $year)->whereMonth('date', $month)->whereDay('date', $day)->orderBy('date', 'DESC')->get();
        }else if ($month){
          $data = DetailJournal::with('journal.account')
          ->whereHas('journal.account.classification.parent', function($q) use($session){
            $q->where('id_business', $session->id);
          })->whereYear('date', $year)->whereMonth('date', $month)->orderBy('date', 'DESC')->get();
        }else {
          $data = DetailJournal::with('journal.account')
          ->whereHas('journal.account.classification.parent', function($q) use($session){
            $q->where('id_business', $session->id);
          })->whereYear('date', $year)->orderBy('date', 'DESC')->get();
        }
        
        $years = DetailJournal::selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();
        
        $array = array();
        $array['jurnal_umum'] = $data;
        $array['available_year'] = $years->pluck('year');

        return new Collection($array);
    }

    public function store(Request $request)
    {
      $debit = 0;
      $credit = 0;
      foreach($request->account as $key => $value){
        $convert = preg_replace("/[^0-9]/", "", $request->amount[$key]);
        if ($request->position[$key] == 'Debit') {
          $debit += (int)$convert;
        }else {
          $credit += (int)$convert;
        }

        $account = InitialBalance::with('account')->where('id_account', $request->account[$key])
          ->whereYear('date', $request->date)->first(); 

        if($account == null){
          $account = Account::where('id', $request->account[$key])->first();
          if($account->position == "Debit"){
            if($request->position[$key] == 'Kredit'){
              return response()->json(['success'=>false,'errors'=>'Saldo tidak cukup.'], 400);
            }
          }else if($account->position == "Kredit"){
            if($request->position[$key] == 'Debit'){
              return response()->json(['success'=>false,'errors'=>'Saldo tidak cukup.'], 400);
            }
          }
        }else {
          if ($account->account->position != $request->position[$key]) {
            if ($account->amount < $request->amount[$key]){
              return response()->json(['success'=>false,'errors'=>'Saldo tidak cukup.'], 400);
            }
          }
        }
      }

      if($debit != $credit){
        return response()->json(['success'=>false,'errors'=>'Jurnal tidak balance.'], 400);
      }

      $detail = new DetailJournal();
      $detail->receipt = $request->receipt;
      $detail->description = $request->description;
      $detail->date = $request->date;
      $detail->save();

      foreach($request->account as $key => $value){
        $jurnal = new GeneralJournal();
        $jurnal->position = $request->position[$key];
        $jurnal->id_detail = $detail->id;
        $jurnal->id_account = $request->account[$key];
        $jurnal->amount = preg_replace("/[^0-9]/", "", $request->amount[$key]);
        $jurnal->save();
      }

      return response()->json([
        'success'=>true,
        'data'=>$detail,
      ]);
    }
    
    public function update(Request $request, $id)
    {
      $debit = 0;
      $credit = 0;
      foreach($request->account as $key => $value){
        $convert = preg_replace("/[^0-9]/", "", $request->amount[$key]);
        if ($request->position[$key] == 'Debit') {
          $debit += (int)$convert;
        }else {
          $credit += (int)$convert;
        }

        $account = InitialBalance::with('account')->where('id_account', $request->account[$key])
          ->whereYear('date', $request->date)->first(); 

        if($account == null){
          $account = Account::where('id', $request->account[$key])->first();
          if($account->position == "Debit"){
            if($request->position[$key] == 'Kredit'){
              return response()->json(['success'=>false,'errors'=>'Saldo tidak cukup.'], 400);
            }
          }else if($account->position == "Kredit"){
            if($request->position[$key] == 'Debit'){
              return response()->json(['success'=>false,'errors'=>'Saldo tidak cukup.'], 400);
            }
          }
        }else {
          if ($account->account->position != $request->position[$key]) {
            if ($account->amount < $request->amount[$key]){
              return response()->json(['success'=>false,'errors'=>'Saldo tidak cukup.'], 400);
            }
          }
        }
      }

      if($debit != $credit){
        return response()->json(['success'=>false,'errors'=>'Jurnal tidak balance.'], 400);
      }

      $detail = DetailJournal::with('journal')->where('id', $id)->first();
      $detail->receipt = $request->receipt;
      $detail->description = $request->description;
      $detail->date = $request->date;
      $detail->save();

      $i = 0;
      foreach($detail->journal as $journal){
        $jurnal = GeneralJournal::findOrFail($journal->id);
        $jurnal->position = $request->position[$i];
        $jurnal->id_detail = $detail->id;
        $jurnal->id_account = $request->account[$i];
        $jurnal->amount = preg_replace("/[^0-9]/", "", $request->amount[$key]);
        $jurnal->save();
        $i++;
      }

      return response()->json([
        'success'=>true,
        'data'=>$detail,
      ]);
    }

    public function destroy($id)
    {
      DetailJournal::destroy($id);
      return response()->json([
        'success'=>true,
        'message'=>'Data berhasil dihapus',
      ]); 
    }
    
    public function search(Request $request)
    {
        if(isset($_GET['year']) || isset($_GET['month']) || isset($_GET['day'])){
          if (isset($_GET['year'], $_GET['month'], $_GET['day'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
            $day = $_GET['day'];
          } elseif(isset($_GET['year'], $_GET['month'])){
            $year = $_GET['year'];
            $month = $_GET['month'];
            $day = null;
          } elseif(isset($_GET['year'])){
            $year = $_GET['year'];
            $month = null;
            $day = null;
          }
        } else {
          $year = date('Y');
          $month = null;
          $day = null;
        }
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

        
        $keyword = ($request['query'] != null) ? $request['query'] : "";

        if ($day && $month) {
          $data = DetailJournal::with('journal.account')
          ->whereHas('journal.account.classification.parent', function($q) use($session, $keyword){
            $q->where('id_business', $session->id);
          })->whereYear('date', $year)->whereMonth('date', $month)->whereDay('date', $day)
          ->where('description','like','%'.$keyword.'%')->orderBy('date', 'DESC')->get();
        }else if ($month){
          $data = DetailJournal::with('journal.account')
          ->whereHas('journal.account.classification.parent', function($q) use($session, $keyword){
            $q->where('id_business', $session->id);
          })->whereYear('date', $year)->whereMonth('date', $month)
          ->where('description','like','%'.$keyword.'%')->orderBy('date', 'DESC')->get();
        }else {
          $data = DetailJournal::with('journal.account')
          ->whereHas('journal.account.classification.parent', function($q) use($session, $keyword){
            $q->where('id_business', $session->id);
          })->whereYear('date', $year)
          ->where('description','like','%'.$keyword.'%')->orderBy('date', 'DESC')->get();
        }
        
        $years = DetailJournal::selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();
        
        $array = array();
        $array['jurnal_umum'] = $data;
        $array['available_year'] = $years->pluck('year');

        return new Collection($array);
    }

}