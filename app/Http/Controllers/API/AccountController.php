<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Http\Resources\Collection;
use Auth;
use App\Account;

class AccountController extends Controller
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

        $account = Account::select('id', 'id_classification', 'account_code', 'account_name', 'position')
          ->whereHas('classification.parent.business.company', function ($query) use ($user) {
            $query->where('id_user', $user->id);
          })->get();

        return new Collection($account);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function classification($id)
    {
        $account = Account::select('id', 'id_classification', 'account_code', 'account_name', 'position')
          ->where('id_classification', $id)->get();

        return new Collection($account);

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
          'id_classification' => ['required', 'exists:account_classifications,id'],
          'name' => ['required', 'string'],
          'position' => ['required'],
          'code' => [
            'required',
            Rule::unique('accounts', 'account_code')->where(function ($query) use($request) {
              return $query->where('id_classification', $request->id_classification);
            }),
          ]
        ],
        [
          'id_classification.required' => 'Klasifikasi akun tidak boleh kosong',
          'id_parent.exists' => 'Klasifikasi akun tidak terdaftar dalam sistem',
          'name.required' => 'Nama akun tidak boleh kosong',
          'position.required' => 'Posisi akun tidak boleh kosong',
          'code.required' => 'Kode akun tidak boleh kosong',
          'code.unique' => 'Kode akun tidak boleh sama',
        ]);

        if ($validator->fails()) {
          return response()->json(['success'=>false,'errors'=>$validator->errors()], 400);
        }

        $data = new Account;
        $data->id_classification = $request->id_classification;
        $data->account_code = $request->name;
        $data->account_name = $request->code;
        $data->position = $request->position;
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
        'id_classification' => ['required', 'exists:account_classifications,id'],
        'name' => ['required', 'string'],
        'position' => ['required'],
        'code' => [
          'required',
          Rule::unique('accounts', 'account_code')->ignore($id)->where(function ($query) use($request) {
            return $query->where('id_classification', $request->id_classification);
          }),
        ]
      ],
      [
        'id_classification.required' => 'Klasifikasi akun tidak boleh kosong',
        'id_parent.exists' => 'Klasifikasi akun tidak terdaftar dalam sistem',
        'name.required' => 'Nama akun tidak boleh kosong',
        'position.required' => 'Posisi akun tidak boleh kosong',
        'code.required' => 'Kode akun tidak boleh kosong',
        'code.unique' => 'Kode akun tidak boleh sama',
      ]);

      if ($validator->fails()) {
        return response()->json(['success'=>false,'errors'=>$validator->errors()], 400);
      }

      $data = Account::findOrFail($id);
      $data->id_classification = $request->id_classification;
      $data->account_code = $request->name;
      $data->account_name = $request->code;
      $data->position = $request->position;
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
      $data = Account::destroy($id);
      return response()->json([
        'success'=>true,
        'message'=>'Data berhasil dihapus',
      ]); 
    }
}
