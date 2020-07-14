<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Companies;
use App\Business;
use App\Employee;

class UserController extends Controller
{

    public $successStatus = 200;

    public function login(Request $request){
      //Autentikasi user
      if(Auth::attempt([
        'email' => request('email'),
        'password' => request('password')
      ])){
        $user = Auth::user();
        if ($user->hasRole('company') || $user->hasRole('employee'))
        {
          $role = $user->getRoleNames()->first();
          
          return response()->json([
            'success'=>true,
            'user' => $user,
            'role' => $role,
            'token' => $user->createToken('SIK_Bumdes')->accessToken
          ]);
        }
        return response()->json([
          'success'=>false,
          'error' => 'Akun bukan merupakan jenis akun perusahaan.'
        ]);
      }
      else{
        return response()->json(['success'=>false,'error'=>'Email atau password salah. Mohon coba lagi'], 400);
      }
    }

    public function register(Request $request)
    {
      //Validasi data
      $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:191',
        'email' => 'required|string|email|max:191|unique:users',
        'password' => 'required|min:8|confirmed',
        'phone_number' => 'required|numeric|digits_between:10,15|unique:companies',
        'address' => 'required|string|max:255',
      ],[
        'email.required' => 'Email tidak boleh kosong',
        'email.max' => 'Email maksimal 255 karakter',
        'email.email' => 'Email tidak valid',
        'email.unique' => 'Email sudah dipakai',
        'name.required' => 'Nama lengkap tidak boleh kosong',
        'name.string' => 'Nama lengkap harus berupa huruf',
        'phone_number.digits_between' => 'No telp tidak boleh kurang dari 10 angka dan lebih dari 15 angka',
        'phone_number.unique' => 'No telp sudah terdaftar',
        'password.min' => 'Password tidak boleh kurang dari 8 karakter',
        'password.required' => 'Password tidak boleh kosong',
        'password.confirmed' => 'Konfirmasi password tidak sama',
        'address.required' => 'Alamat tidak boleh kosong',
      ]);
		  if ($validator->fails()) {
        return response()->json(['success'=>false,'errors'=>$validator->errors()], 400);
      }
      
      $user = User::create([
        'email' => $request->email,
        'password' => bcrypt($request->password),
      ]);

      $user->assignRole('company');

      $detail_user = Companies::create([
        'name'=> $request->name,
        'address' => $request->address,
        'phone_number' => $request->phone_number,
        'id_user' => $user->id,
      ]);

		  return response()->json([
        'success'=>true,
        'user' => $user, 
        'company' => $detail_user
      ], $this->successStatus); 
    }
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
            $company = Companies::where('id_user', $user->id)->first();
            $business = Business::where('id_company', $company->id)->get();
        } else {
            $company = Employee::where('id_user', $user->id)->first();
            $business = Employee::with('business')->where('id_user', $user->id)->first()->business;
        }

        return response()->json([
          'success'=>true,
          'user' => $user, 
          'detail' => $company,
          'business' => $business,
        ], $this->successStatus); 
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      $id = Auth::id();

      $isCompany = $user->hasRole('company');
        
      if($isCompany){
        $detail = Companies::where('id_user', $id)->first();

        $validator = Validator::make($request->all(), [
          'name' => 'required|string|max:191',
          'email' => 'required|string|email|max:191|unique:users,email,'.$id,
          'phone_number' => 'required|numeric|digits_between:10,15|unique:companies,phone_number,'.$detail->id,
          'address' => 'required|string|max:255',
        ],[
          'email.required' => 'Email tidak boleh kosong',
          'email.max' => 'Email maksimal 255 karakter',
          'email.email' => 'Email tidak valid',
          'email.unique' => 'Email sudah dipakai',
          'name.required' => 'Nama lengkap tidak boleh kosong',
          'name.string' => 'Nama lengkap harus berupa huruf',
          'phone_number.digits_between' => 'No telp tidak boleh kurang dari 10 angka dan lebih dari 15 angka',
          'phone_number.unique' => 'No telp sudah terdaftar',
          'address.required' => 'Alamat tidak boleh kosong',
        ]);
        if ($validator->fails()) {
          return response()->json(['success'=>false,'errors'=>$validator->errors()], 400);
        }
        
        $data = User::findOrFail($id);
        $data->email = $request->email;
        $data->save();
        
        $detail->name = $request->name;
        $detail->address = $request->address;
        $detail->phone_number = $request->phone_number;
        $detail->save();
        
        return response()->json([
          'success'=>true,
          'user'=>$data,
          'company'=>$detail,
        ]);
      } else {
        $validator = Validator::make($request->all(), [
          'name' => 'required|string|max:191',
          'email' => 'required|string|email|max:191|unique:users,email,'.$id,
        ],[
          'email.required' => 'Email tidak boleh kosong',
          'email.max' => 'Email maksimal 255 karakter',
          'email.email' => 'Email tidak valid',
          'email.unique' => 'Email sudah dipakai',
          'name.required' => 'Nama lengkap tidak boleh kosong',
          'name.string' => 'Nama lengkap harus berupa huruf',
        ]);
        if ($validator->fails()) {
          return response()->json(['success'=>false,'errors'=>$validator->errors()], 400);
        }
        
        $data = User::findOrFail($id);
        $data->email = $request->email;
        $data->save();
        
        $detail = Employee::where('id_user', $user->id)->first();
        $detail->name = $request->name;
        $detail->save();
        
        return response()->json([
          'success'=>true,
          'user'=>$data,
          'detail'=>$detail,
        ]);
      }
    }
}
