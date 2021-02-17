<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\Collection;
use Auth;
use App\BusinessSession;
use App\InitialBalance;
use App\Business;
use App\Account;
use App\GeneralJournal;
use App\DetailJournal;

class GeneralLedgerController extends Controller
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
      if(!$session->business){
        return response()->json(['success'=>false,'error'=>'Sesi bisnis belum dipilih.'], 400);
      }
      $session = $session->business;
      $account = Account::with('classification.parent')
      ->whereHas('classification.parent', function ($query) use ($session) {
        $query->where('id_business', $session->id);
      })->first()->id;

      if(isset($_GET['year'], $_GET['akun'])){
          $year = $_GET['year'];
          $akun = $_GET['akun'];
      }else if (isset($_GET['year'])){
          $year = $_GET['year'];
          $akun = $account;
      }else if (isset($_GET['akun'])) {
          $year = date('Y');
          $akun = $_GET['akun'];
      } else {
          $year = date('Y');
          $akun = $account;
      }
      // Menampilkan data detail akun dan neraca awalnya
      $checkAccount = Account::with('initialBalance', 'journal', 'classification.parent')
      ->whereHas('classification.parent', function ($query) use ($session) {
        $query->where('id_business', $session->id);
      })->where('id', $akun)->first();  

      $log = array();
      if(!$checkAccount->initialBalance()->whereYear('date', $year)->first()){
          $beginning_balance = 0;
      } else {
          $beginning_balance = $checkAccount->initialBalance()->whereYear('date', $year)->first()->amount;
      }
      $log['nama_akun'] = $checkAccount->account_name;
      $log['kode_akun'] = $checkAccount->account_code;
      $log['position'] = $checkAccount->position;
      $log['saldo_awal'] = $beginning_balance;
      if(!$checkAccount->initialBalance()->whereYear('date', $year)->first()){
          $log['date'] = '';    
      } else {
          $log['date'] = $checkAccount->initialBalance()->first()->date;
      }
      
      $data = GeneralJournal::with('account.classification.parent')
        ->whereHas('account.classification.parent', function($q) use($session){
            $q->where('id_business', $session->id);
        })
        ->join('journal_detail', 'general_journals.id_detail', '=', 'journal_detail.id')
        ->whereYear('journal_detail.date', $year)
        ->where('id_account', $akun)
        ->orderBy('journal_detail.date')
      ->get();

      $debit = 0;
      $kredit = 0;
      $saldo_akhir = $log['saldo_awal'];
      $buku_besar = array();
      $buku_besar[0]['date'] = $log['date'];
      $buku_besar[0]['description'] = 'Saldo Awal';
      $buku_besar[0]['position'] = '';
      $buku_besar[0]['amount'] = '';
      $buku_besar[0]['saldo'] = $saldo_akhir;
      $i = 1;
      foreach ($data as $d) {
        if ($d->position=="Debit"){
          if ($d->amount < 0){
            $amount = -1*$d->amount;
          } else {
            $amount = $d->amount;
          }
          $debit += $amount;
        }else if ($d->position=="Kredit"){
          if ($d->amount < 0){
            $amount = -1*$d->amount;
          } else {
            $amount = $d->amount;
          }
          $kredit += $amount;
        }
        if ($log['position'] == "Debit"){
          if ($d->position == "Kredit")
            $saldo_akhir -= $d->amount;
          else if ($d->position == "Debit"){
            $saldo_akhir += $d->amount;
          }
        }else if ($log['position'] == "Kredit") {
          if ($d->position == "Kredit")
            $saldo_akhir += $d->amount;
          else if ($d->position == "Debit"){
            $saldo_akhir -= $d->amount;
          }
        }
        $buku_besar[$i]['date'] = $d->date;
        $buku_besar[$i]['description'] = $d->description;
        $buku_besar[$i]['position'] = $d->position;
        $buku_besar[$i]['amount'] = $d->amount;
        $buku_besar[$i]['saldo'] = $saldo_akhir;
        $i++;
      }
      $log['saldo_akhir'] = $saldo_akhir;

      $akuns = Account::
      whereHas('classification.parent', function ($query) use ($session) {
        $query->where('id_business', $session->id);
      })->get();

      $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
          $q->where('id_business', $session->id);
      })->selectRaw('YEAR(date) as year')
      ->orderBy('date', 'desc')->distinct()->get();      
      
      $array = array();
      $array['logs'] = $log;
      $array['available_year'] = $years->pluck('year');
      if(!isset($_GET['akun'])){
        $array['available_account'] = $akuns;
      }
      $array['buku_besar'] = $buku_besar;

      return new Collection($array);
    }
}
