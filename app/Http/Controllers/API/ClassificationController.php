<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Http\Resources\Collection;
use Auth;
use App\AccountClassification;
use App\BusinessSession;

class ClassificationController extends Controller
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
        if(!$session->business){
          return response()->json(['success'=>false,'error'=>'Sesi bisnis belum dipilih.'], 400);
        }
        $session = $session->business;

        $classification = AccountClassification::select('id', 'id_parent', 'classification_code', 'classification_name')
        ->whereHas('parent', function ($query) use ($session) {
          $query->where('id_business', $session->id);
        })->get();

        return new Collection($classification);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function parent($id)
    {
        $classification = AccountClassification::select('id', 'id_parent', 'classification_code', 'classification_name')
          ->where('id_parent', $id)->get();

        return new Collection($classification);

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
        'id_parent' => ['required', 'exists:account_parent,id'],
        'name' => ['required', 'string'],
        'code' => [
          'required',
          Rule::unique('account_classifications', 'classification_code')->where(function ($query) use($request) {
            return $query->where('id_parent', $request->id_parent);
          }),
        ]
      ],
      [
        'id_parent.required' => 'Parent tidak boleh kosong',
        'id_parent.exists' => 'Parent tidak terdaftar dalam sistem',
        'name.required' => 'Nama klasifikasi akun tidak boleh kosong',
        'code.required' => 'Kode klasifikasi akun tidak boleh kosong',
        'code.unique' => 'Kode klasifikasi akun tidak boleh sama',
      ]);
      
      if ($validator->fails()) {
        return response()->json(['success'=>false,'errors'=>$validator->errors()], 400);
      }

      $data = new AccountClassification;
      $data->id_parent = $request->id_parent;
      $data->classification_code = $request->code;
      $data->classification_name = $request->name;
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
        'id_parent' => ['required', 'exists:account_parent,id'],
        'name' => ['required', 'string'],
        'code' => [
          'required',
          Rule::unique('account_classifications', 'classification_code')->ignore($id)->where(function ($query) use($request) {
            return $query->where('id_parent', $request->id_parent);
          }),
        ]
      ],
      [
        'id_parent.required' => 'Parent tidak boleh kosong',
        'id_parent.exists' => 'Parent tidak terdaftar dalam sistem',
        'name.required' => 'Nama klasifikasi akun tidak boleh kosong',
        'code.required' => 'Kode klasifikasi akun tidak boleh kosong',
        'code.unique' => 'Kode klasifikasi akun tidak boleh sama',
      ]);
      
      if ($validator->fails()) {
        return response()->json(['success'=>false,'errors'=>$validator->errors()], 400);
      }

      $data = AccountClassification::findOrFail($id);
      $data->id_parent = $request->id_parent;
      $data->classification_code = $request->code;
      $data->classification_name = $request->name;
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
      $data = AccountClassification::destroy($id);
      return response()->json([
        'success'=>true,
        'message'=>'Data berhasil dihapus',
      ]); 
    }
}
