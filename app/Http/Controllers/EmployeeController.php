<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use App\Mail\AccountRegisteredMail;
use Auth;
use App\Companies;
use App\Business;
use App\User;
use App\Employee;

class EmployeeController extends Controller
{

    public function __construct()
    {
        $this->middleware(['role:owner|employee']);

        $this->middleware('auth');
        
    }
 
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

    public function create()
    {
        $session = session('business');

        $user = Auth::user()->id;

        $company = Companies::where('id_user', $user)->first()->id;
        $business = Business::where('id_company', $company)->get();
        
        return view('user/tambahKaryawan', compact('business', 'session'));
    }

    public function store(Request $request)
    {

        $this->validate($request,[
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        
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

        $user = ([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
            ]);

        Mail::to($request->email)->send(new AccountRegisteredMail($user));

        return redirect()->route('karyawan.index')->with('success','Berhasil Menambahkan Karyawan!');

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $data = Employee::where('id', $id)->first();

        $data->name = $request->name;
        $data->id_business = $request->id_business;
        
        $data->save();

        return redirect()->route('karyawan.index')->with('success','Berhasil Mengubah Karyawan!');
    }

    public function destroy($id)
    {
        Employee::findOrFail($id)->delete($id);

        return response()->json([
            'success'  => 'Record deleted successfully!'
        ]);
    }

    public function detailEmployee(Request $request)
    {
        $account = Employee::with('user','business')->where('id', $request->id)
        ->get();

        return response()->json($account);
    }
}
