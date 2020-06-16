<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
use App\Employee;
use App\Companies;
use App\Business;
use App\User;


class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:company|employee']);

        $this->middleware('auth');
        
    }

    public function isPro(){
        $user = Auth::user();

        $data = Companies::with('user')->where('id_user', $user->id)->first()->is_actived;

        if ($data == 1) {
            return response()->json([
                'status' => 'success',
                'result' => 'PRO',
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'result' => 'REGULER',
            ]);
        }
    }

    public function index()
    {
        $user = Auth::user();
        $isCompany = $user->hasRole('company');
        if($isCompany){
            $session = session('business');
            $company = Companies::where('id_user', $user->id)->first()->id;
            $business = Business::where('id_company', $company)->get();
            if($session == null){
                $session = Business::where('id_company', $company)->first()->id;
            }
            $getBusiness = Business::with('company')
            ->where('id_company', $company)
            ->where('id', $session)->first();
            $data = Companies::with('user')->where('id_user', $user->id)->first();
        } else {
            $getBusiness = Employee::with('business')->where('id_user', $user->id)->first();
            $session = $getBusiness->id_business;
            $data = Employee::with('user', 'business', 'company')->where('id_user', $user->id)->first();
        }
        return view('user.profile', compact('session', 'business', 'data', 'getBusiness'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $this->validate($request,[
            'email' => "required|string|email|max:255|unique:users,email,$request->id_user",
            'name' => 'required|string|max:191',
            'phone_number' => "required|min:10|max:15|unique:companies,phone_number,$request->id",
            'address' => 'required|string|max:255'
        ],
        [
            'email.required' => 'Email tidak boleh kosong',
            'email.max' => 'Email maksimal 255 karakter',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah dipakai',
            'name.required' => 'Nama lengkap tidak boleh kosong',
            'name.string' => 'Nama lengkap harus berupa huruf',
            'phone_number.min' => 'No telp tidak boleh kurang dari 10 angka',
            'phone_number.max' => 'No telp tidak boleh lebih dari 15 angka',
            'phone_number.unique' => 'No telp sudah terdaftar',
            'address.required' => 'Alamat tidak boleh kosong',
            'address.max' => 'Alamat tidak boleh lebih dari 255 karakter',
        ]);
        
        $user = User::findOrFail($request->id_user);
        $user->email = $request->email;
        $user->save();

        $company = Companies::findOrFail($request->id);
        $company->name = $request->name;
        $company->address = $request->address;
        $company->phone_number = $request->phone_number;
        $company->save();

        return redirect()->route('profile.index')->with('success','Berhasil Mengubah Profil!');
    }

    public function updateEmployee(Request $request)
    {
        $this->validate($request,[
            'email' => "required|string|email|max:255|unique:users,email,$request->id_user",
            'name' => 'required|string|max:191',
        ],
        [
            'email.required' => 'Email tidak boleh kosong',
            'email.max' => 'Email maksimal 255 karakter',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah dipakai',
            'name.required' => 'Nama lengkap tidak boleh kosong',
            'name.string' => 'Nama lengkap harus berupa huruf',
        ]);
        $user = User::findOrFail($request->id_user);
        $user->email = $request->email;
        $user->save();

        $company = Employee::findOrFail($request->id);
        $company->name = $request->name;
        $company->save();

        return redirect()->route('profile.index')->with('success','Berhasil Mengubah Profil!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function upgrade()
    {
        $user = Auth::user();
        $isCompany = $user->hasRole('company');
        if($isCompany){
            $data = Companies::with('user')->where('id_user', $user->id)->first()->is_actived;
            if($data == 0 ){
                $session = session('business');
                $company = Companies::where('id_user', $user->id)->first()->id;
                $business = Business::where('id_company', $company)->get();
                if($session == null){
                    $session = Business::where('id_company', $company)->first()->id;
                }
                $getBusiness = Business::with('company')
                ->where('id_company', $company)
                ->where('id', $session)->first();
                $data = Companies::with('user')->where('id_user', $user->id)->first();
                return view('user.home', compact('session', 'getBusiness', 'business'));
            }else {
                abort(403);
            }
        } else {
            abort(403);
        }
    }
}
