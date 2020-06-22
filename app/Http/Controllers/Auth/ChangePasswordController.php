<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;
use App\Companies;
use App\Business;
use App\Employee;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $isCompany = $user->hasRole('company');
        if($user->hasRole('company') || $user->hasRole('employee')){
            if($isCompany){
                $session = session('business');
                $company = Companies::where('id_user', $user->id)->first()->id;
                $business = Business::where('id_company', $company)->get();
                if($session == null){
                    $session = Business::where('id_company', $company)->first()->id;
                }
                $getBusiness = Business::with('company')
                ->where('id_company', $company)->where('id', $session)->first();
            } else {
                $getBusiness = Employee::with('business')->where('id_user', $user->id)->first();
                $session = $getBusiness->id_business;
            }
            return view('user.changePassword', compact('session', 'getBusiness', 'business'));
        } else if($user->hasRole('super admin') || $user->hasRole('admin')){
            return view('admin.changePassword');
        }
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
        // dd($request->new_confirm_password);
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required', 'min:8'],
            'new_confirm_password' => ['same:new_password'],
        ],
        [
            'new_password.required' => 'Password baru tidak boleh kosong',
            'new_password.min' => 'Password baru minimal 8 karakter',
        ]);

        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);

        return redirect()->route('ganti_password.index')->with('toast_success','Berhasil Mengubah kata Sandi!');
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
    public function update(Request $request, $id)
    {
        //
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
}
