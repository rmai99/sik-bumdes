<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
use App\Companies;
use App\Business;
use App\Employee;
use App\AccountParent;
use App\Account;
use App\AccountClassification;


class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $role = Auth::user();
        $isOwner = $role->hasRole('owner');
        $user = Auth::user()->id;
        
        if($isOwner){
            $session = session('business');
            
            $company = Companies::where('id_user', $user)->first()->id;
            
            $business = Business::where('id_company', $company)->get();
            $getBusiness = Business::where('id_company', $company)->first()->id;
            
            if($session == 0){
                $session = $getBusiness;
            }
        } 
        else 
        {
            $getBusiness = Employee::where('id_user', $user)->select('id_business')->first();
            $idBusiness= $getBusiness->id_business;

            $session = $idBusiness;

        }
        $account_parent = AccountParent::with('classification.account')
        ->where('id_business', $session)
        ->orderby('parent_code')->get();
        
        return view('user.akun', compact('account_parent','business', 'session'));
    }

    public function detailAccount(Request $request)
    {
        $account = Account::where('id', $request->id)
        ->get();

        return response()->json($account);
    }
    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        
        $akunSave = new Account;
        $akunSave->id_classification = $request->input('classificationAkun');
        $akunSave->account_code = $request->input('codeAkun');
        $akunSave->account_name = $request->input('akun');
        $akunSave->position = $request->input('position');
        $akunSave->save();

        return redirect()->route('akun.index')->with('success','Berhasil Menambahkan Data!');
    }

    public function show($id)
    {
        
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $data = Account::where('id',$id)->first();

        $data->id_classification = $request->id_classification;
        $data->account_code = $request->numberCode;
        $data->account_name = $request->name;
        $data->position = $request->position;
        $data->save();

        return redirect()->route('akun.index')->with('success','Berhasil Mengubah Data!');
    }

    public function destroy($id)
    {
        $data = Account::where('id',$id)->first();
        $data->delete();

        return redirect()->route('akun.index')->with('success','Akun berhasil di hapus');
    }
}
