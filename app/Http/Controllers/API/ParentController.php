<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\Collection;
use Auth;
use App\AccountParent;
use App\BusinessSession;
use App\Companies;
use App\Employee;

class ParentController extends Controller
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

        $account_parent = AccountParent::select('id', 'id_business', 'parent_code', 'parent_name')
        ->where('id_business', $session->id)->get();

        return new Collection($account_parent);

    }

    public function indexChild()
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
        $account_parent = AccountParent::select('id', 'id_business', 'parent_code', 'parent_name')->with([
          'classification' => function ($query) {
            $query->select('id', 'id_parent', 'classification_code', 'classification_name');
          }, 
          'classification.account' => function ($query) {
            $query->select('id', 'id_classification', 'account_code', 'account_name', 'position');
          }
        ])
        ->where('id_business', $session->id)->get();

        return new Collection($account_parent);

    }
}