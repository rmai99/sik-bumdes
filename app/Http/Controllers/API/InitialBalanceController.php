<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\Collection;
use Auth;
use App\Companies;
use App\Business;
use App\AccountParent;
use App\InitialBalance;
use App\Employee;

class InitialBalanceController extends Controller
{

    public $successStatus = 200;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $user = Auth::guard('api')->user();

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
        ->where('id_business', $id)->get();

        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($id){
            $q->where('id_business', $id);
        })->selectRaw('YEAR(date) as year')
        ->orderBy('date', 'desc')->distinct()->get();

        $array = array();
        $array['neraca_awal'] = $account_parent;
        $array['available_year'] = $years->pluck('year');

        return new Collection($array);

    }
}
