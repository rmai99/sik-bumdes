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
use App\BudgetAccount;
use App\AccountBudgetCategory;

class BudgetAccountController extends Controller
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
        $account = AccountBudgetCategory::with(['budget_account' => function ($query) use ($company) {
            $query->where('id_company', $company);
        }])->get();

        $type = BudgetAccount::where('id_company', $company)->where('type','Belanja')->get();

        $array = array();
        $array['penerimaan'] = $account;
        $array['belanja'] = $type;

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
          'type' => ['required'],
          'name' => ['required']
        ],
        [
          'type.required' => 'Tipe anggaran tidak boleh kosong',
          'name.required' => 'Nama anggaran tidak boleh kosong',
        ]);

        if ($validator->fails()) {
          return response()->json(['success'=>false,'errors'=>$validator->errors()], 400);
        }

        $user = Auth::guard('api')->user();
        $isCompany = $user->hasRole('company');
        if($isCompany){
            $company = Companies::where('id_user', $user->id)->first()->id;
        } else {
            $getBusiness = Employee::with('business')->where('id_user', $user->id)->first();
            $company = $getBusiness->id_company;
        }
        $data = new BudgetAccount();
        $data->type = $request->type;
        $data->id_category = $request->id_category;
        $data->name = $request->name;
        $data->id_company = $company;
        $data->save();

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
        'type' => ['required'],
        'name' => ['required']
      ],
      [
        'type.required' => 'Tipe anggaran tidak boleh kosong',
        'name.required' => 'Nama anggaran tidak boleh kosong',
      ]);

      if ($validator->fails()) {
        return response()->json(['success'=>false,'errors'=>$validator->errors()], 400);
      }

      $data = BudgetAccount::where('id', $id)->first();
      $data->type = $request->type;
      $data->id_category = $request->id_category;
      $data->name = $request->name;
      $data->save();

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
      $data = BudgetAccount::destroy($id);
      return response()->json([
        'success'=>true,
        'message'=>'Data berhasil dihapus',
      ]); 
    }
}
