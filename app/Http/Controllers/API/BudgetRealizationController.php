<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Http\Resources\Collection;
use Auth;
use App\Companies;
use App\Business;
use App\Employee;
use App\BudgetPlan;
use App\AccountBudgetCategory;
use App\BudgetAccount;
use App\BudgetPlanRealization;

class BudgetRealizationController extends Controller
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
        $isCompany = $user->hasRole('company');
        if($isCompany){
            $company = Companies::where('id_user', $user->id)->first()->id;
        } else {
            $getBusiness = Employee::with('business')->where('id_user', $user->id)->first();
            $company = $getBusiness->id_company;
        }
        if (isset($_GET['year'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }
        
        $account_plan = AccountBudgetCategory::with(['budget_account' => function ($query) use ($company) {
            $query->where('id_company', $company);
        },'budget_account.budget_plan' => function ($query) use ($month, $year) {
            $query->whereYear('date', $year);
            $query->whereMonth('date', $month);
        },'budget_account.budget_plan.realization'])->get();
      
        $type = BudgetAccount::with(['budget_plan' => function ($query) use ($month, $year) {
            $query->whereYear('date', $year);
            $query->whereMonth('date', $month);
        },'budget_plan.realization'])->where('id_company', $company)->where('type','Belanja')->get();

        $account = BudgetAccount::where('id_company', $company)->get();

        $years = BudgetPlan::whereHas('budget_account', function($q) use ($company){
            $q->where('id_company', $company);
        })->selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();

        $array = array();
        $array['penerimaan'] = $account_plan;
        $array['belanja'] = $type;
        $array['akun_anggaran'] = $account;
        $array['available_year'] = $years;

        return new Collection($array);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
          'amount' => ['required'],
          'id_rencana_anggaran' => ['required'],
        ],
        [
          'amount.required' => 'Jumlah realisasi tidak boleh kosong',
          'id_rencana_anggaran.required' => 'Rencana anggaran tidak boleh kosong',
        ]);

        if ($validator->fails()) {
          return response()->json(['success'=>false,'errors'=>$validator->errors()], 400);
        }

        $data = new BudgetPlanRealization();
        $data->id_budget_plan = $request->id_rencana_anggaran;
        $data->amount = $request->amount;
        $data->save();
        $data->id_budget_plan = $request->id_rencana_anggaran;

        return response()->json([
          'success'=>true,
          'data'=>$data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $validator = Validator::make($request->all(), [
        'amount' => ['required'],
        'id_rencana_anggaran' => ['required'],
      ],
      [
        'amount.required' => 'Jumlah realisasi tidak boleh kosong',
        'id_rencana_anggaran.required' => 'Rencana anggaran tidak boleh kosong',
      ]);

      if ($validator->fails()) {
        return response()->json(['success'=>false,'errors'=>$validator->errors()], 400);
      }

      $data = BudgetPlanRealization::where('id_budget_plan', $request->id_rencana_anggaran)->first();
      $data->id_budget_plan = $request->id_rencana_anggaran;
      $data->amount = $request->amount;
      $data->save();
      $data->id_budget_plan = $request->id_rencana_anggaran;

      return response()->json([
        'success'=>true,
        'data'=>$data,
      ]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $data = BudgetPlanRealization::destroy($id);
      return response()->json([
        'success'=>true,
        'message'=>'Data berhasil dihapus',
      ]); 
    }
}
