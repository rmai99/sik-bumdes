<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\Collection;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Companies;
use App\Business;
use App\AccountParent;
use App\InitialBalance;
use App\Employee;
use App\BusinessSession;

class InitialBalanceController extends Controller
{

    public $successStatus = 200;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::guard('api')->user();

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
        
        if (isset($_GET['year'])) {
            $year = $_GET['year'];
        } else {
            $year = date('Y');
        }
        // dd($year);
        $account_parent = AccountParent::select('id', 'id_business', 'parent_code', 'parent_name')->with([
          'classification' => function ($query) use ($year) {
            $query->select('id', 'id_parent', 'classification_code', 'classification_name')
            ->whereHas('account.initialBalance', function ($query) use ($year) {
              $query->whereYear('date', $year);
            });
          }, 
          'classification.account' => function ($query) use ($year) {
            $query->select('id', 'id_classification', 'account_code', 'account_name', 'position')
            ->whereHas('initialBalance' , function ($query) use ($year) {
              $query->whereYear('date', $year);
            });
          }, 
          'classification.account.initialBalance' => function ($query) use ($year) {
            $query->select('id', 'id_account', 'amount', 'date')
            ->whereYear('date', $year);
          }
        ])
        ->where('id_business', $session->id)->get();

        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session->id);
        })->selectRaw('YEAR(date) as year')
        ->orderBy('date', 'desc')->distinct()->get();

        $total_debit = 0;
        $total_kredit = 0;

        foreach ($account_parent as $parent) {
          foreach ($parent->classification as $classification) {
            foreach ($classification->account as $account) {
              foreach ($account->initialBalance as $initialBalance) {
                if ($account->position == "Debit") {
                  $total_debit += $initialBalance->amount;
                }else {
                  $total_kredit += $initialBalance->amount;
                }
              }
            }
          }
        }

        $array = array();
        $array['total_debit'] = $total_debit;
        $array['total_kredit'] = $total_kredit;
        $array['business'] = $session;
        $array['neraca_awal'] = $account_parent;
        $array['available_year'] = $years->pluck('year');

        return new Collection($array);

    }

    
    public function store(Request $request)
    {
      $dates = date('Y', strtotime($request->date) );
      $data = InitialBalance::where('id_account', $request->id_account)->whereYear('date','=', $dates)->first();
      if($data){
          $dates = date('Y-m-d', strtotime($data->date . " +1 year") );
      }else{
          $dates = 0000-00-00;
      }

      $validator = Validator::make($request->all(), [
        'id_account' => 'required',
        'amount' => 'required',
        'date' => 'required|after_or_equal:'.$dates,
      ],
      [
        'id_account.required' => 'Akun tidak boleh kosong',
        'amount.required' => 'Jumlah tidak boleh kosong',
        'date.required' => 'Tanggal tidak boleh kosong',
        'date.after_or_equal' => 'Penginputan neraca awal hanya sekali dalam setahun',
      ]);

      if ($validator->fails()) {
        return response()->json(['success'=>false,'errors'=>$validator->errors()], 400);
      }

      $convert_amount = preg_replace("/[^0-9]/", "", $request->amount);

      $data = new InitialBalance();
      $data->date = $request->date;
      $data->id_account = $request->id_account;
      $data->amount = $convert_amount;
      $data->save();

      return response()->json([
        'success'=>true,
        'data'=>$data,
      ]);
    }
    
    public function update(Request $request, $id)
    {
      if(InitialBalance::where('id', $id)->where('id_account', $request->id_account)->whereYear('date','=', $request->date)->first()){
          $dates = 0000-00-00;
      } else if(!InitialBalance::where('id_account', $request->id_account)->whereYear('date','=', $request->date)->first()){
          $dates = 0000-00-00;
      } else if(InitialBalance::where('id_account', $request->id_account)->whereYear('date','=', $request->date)->first()){
          $dates = date('Y-m-d', strtotime($request->date . " +1 year") );
      }

      $validator = Validator::make($request->all(), [
        'id_account' => 'required',
        'amount' => 'required',
        'date' => 'required|after_or_equal:'.$dates,
      ],
      [
        'id_account.required' => 'Akun tidak boleh kosong',
        'amount.required' => 'Jumlah tidak boleh kosong',
        'date.required' => 'Tanggal tidak boleh kosong',
        'date.after_or_equal' => 'Penginputan neraca awal hanya sekali dalam setahun',
      ]);

      if ($validator->fails()) {
        return response()->json(['success'=>false,'errors'=>$validator->errors()], 400);
      }

      $data = InitialBalance::where('id', $id)->first();
      $convert_amount = preg_replace("/[^0-9]/", "", $request->amount);
      
      $data->id_account = $request->id_account;
      $data->amount = $convert_amount;
      $data->date = $request->date;
      $data->save();

      return response()->json([
        'success'=>true,
        'data'=>$data,
      ]);
    }

    public function destroy($id)
    {
      try {
        InitialBalance::destroy($id);
      } catch (Throwable $e) {
        return response()->json(['success'=>false,'errors'=>$validator->errors()], 500);
      }
      return response()->json([
        'success'=>true,
        'message'=>'Data berhasil dihapus',
      ]); 
    }

    
    public function search(Request $request)
    {
        $user = Auth::guard('api')->user();

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
        
        if (isset($_GET['year'])) {
            $year = $_GET['year'];
        } else {
            $year = date('Y');
        }
        // dd($year);
        
        $keyword = ($request['query'] != null) ? $request['query'] : "";
        
        $account_parent = AccountParent::select('id', 'id_business', 'parent_code', 'parent_name')->with([
          'classification' => function ($query) use ($year) {
            $query->select('id', 'id_parent', 'classification_code', 'classification_name')
            ->whereHas('account.initialBalance', function ($query) use ($year) {
              $query->whereYear('date', $year);
            });
          }, 
          'classification.account' => function ($query) use ($year, $keyword) {
            $query->select('id', 'id_classification', 'account_code', 'account_name', 'position')
            ->whereHas('initialBalance' , function ($query) use ($year, $keyword) {
              $query->whereYear('date', $year)
              ->where('account_name','like','%'.$keyword.'%');
            });
          }, 
          'classification.account.initialBalance' => function ($query) use ($year, $keyword) {
            $query->select('id', 'id_account', 'amount', 'date')
            ->whereYear('date', $year);
          }
        ])
        ->where('id_business', $session->id)->get();

        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session->id);
        })->selectRaw('YEAR(date) as year')
        ->orderBy('date', 'desc')->distinct()->get();

        $total_debit = 0;
        $total_kredit = 0;

        foreach ($account_parent as $parent) {
          foreach ($parent->classification as $classification) {
            foreach ($classification->account as $account) {
              foreach ($account->initialBalance as $initialBalance) {
                if ($account->position == "Debit") {
                  $total_debit += $initialBalance->amount;
                }else {
                  $total_kredit += $initialBalance->amount;
                }
              }
            }
          }
        }

        $array = array();
        $array['total_debit'] = $total_debit;
        $array['total_kredit'] = $total_kredit;
        $array['business'] = $session;
        $array['neraca_awal'] = $account_parent;
        $array['available_year'] = $years->pluck('year');

        return new Collection($array);

    }
}