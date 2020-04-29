<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
use App\Employee;
use App\Companies;
use App\Business;


class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:owner|employee']);

        $this->middleware('auth');
        
    }

    public function cekpro(){
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
        $role = Auth::user();
        $isOwner = $role->hasRole('owner');
        
        $user = Auth::user()->id;

        if($isOwner){
            $session = session('business');

            $data = Companies::with('user')->where('id_user', $user)->first();
            $company = $data->id;
            
            $business = Business::where('id_company', $company)->get();

            $getBusiness = Business::where('id_company', $company)->first()->id;
            
            if($session == 0){
                $session = $getBusiness;
            }

        } else {
            $getBusiness = Employee::where('id_user', $user)->select('id_business')->first()->id_business;
            
            $session = $getBusiness;

            $data = Employee::with('user', 'business', 'company')->where('id_user', $user)->first();
        }
        return view('user.profile', compact('session', 'business', 'data'));
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
        $company = Companies::findOrFail($request->id);
        $company->name = $request->name;
        $company->address = $request->address;
        $company->phone_number = $request->phone_number;
        $company->save();

        return redirect()->route('profile.index')->with('success','Berhasil Mengubah Profile!');;
    }

    public function updateEmployee(Request $request)
    {
        $company = Employee::findOrFail($request->id);
        $company->name = $request->name;
        $company->save();

        return redirect()->route('profile.index')->with('success','Berhasil Mengubah Profile!');;
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
