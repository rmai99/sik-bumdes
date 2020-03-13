<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Companies;
use App\Business;
use App\User;
use App\Employee;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->id;

        $session = session('business');

        $company = Companies::where('id_user', $user)->first()->id;

        $business = Business::where('id_company', $company)->get();
        
        $employee = Employee::with('user','business')->where('id_company', $company)->get();
        // dd($employee);

        return view('user/karyawan', compact('employee', 'business', 'session'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $session = session('business');

        $user = Auth::user()->id;

        $company = Companies::where('id_user', $user)->first()->id;
        $business = Business::where('id_company', $company)->get();
        
        return view('user/tambahKaryawan', compact('business', 'session'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $user = Auth::user()->id;
        $company = Companies::where('id_user', $user)->first()->id;

        $user = new User();
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        $user->assignRole('employee');

        $data = new Employee();
        $data->name = $request->name;
        $data->id_user = $user->id;
        $data->id_company = $company;
        $data->id_business = $request->id_business;
        $data->save();

        return redirect()->route('karyawan.index')->with('success','Berhasil Menambahkan Karyawan!');

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
        $data = Employee::where('id', $id)->first();
        $data->delete();

        return redirect()->back()->with('success','Berhasil menghapus Karyawan!');
    }

    public function detailEmployee(Request $request)
    {
        $account = Employee::with('user','business')->where('id', $request->id)
        ->get();
        // dd($account);

        return response()->json($account);
    }
}
