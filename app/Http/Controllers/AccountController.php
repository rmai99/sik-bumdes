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
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:company|employee']);
        $this->middleware('auth');
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
            ->where('id_company', $company)->where('id', $session)->first();
        } else {
            $getBusiness = Employee::with('business')->where('id_user', $user->id)->first();
            $session = $getBusiness->id_business;
        }
        
        $account_parent = AccountParent::with('classification.account')
        ->where('id_business', $session)
        ->orderby('parent_code')->get();
        
        return view('user.akun', compact('account_parent','business', 'session', 'getBusiness'));
    }

    public function detailAccount(Request $request)
    {
        $account = Account::where('id', $request->id)->get();
        return response()->json($account);
    }
    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $business = AccountParent::where('id',$request->input_parentAccount)->first()->id_business;
        
        $data = Account::whereHas('classification.parent', function ($q) use($business){
            $q->where('id_business', $business);
        })->get();

        foreach($data as $d){
            $array[] = $d->account_code;
        }
        
        $this->validate($request,[
            'input_codeAccount' => Rule::notIn($array),
        ],
        [
            'input_codeAccount.not_in' => 'Kode akun tidak boleh sama',
        ]);

        $data = new Account;
        $data->id_classification = $request->input('input_classificationAccount');
        $data->account_code = $request->input('input_codeAccount');
        $data->account_name = $request->input('input_nameAccount');
        $data->position = $request->input('input_positionAccount');
        $data->save();

        return redirect()->route('akun.index')->with('success','Berhasil Menambahkan Akun!');
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
        $business = AccountParent::where('id',$request->edit_parentAccount)->first()->id_business;
        
        $data = Account::where('id',$id)->first();

        $code = Account::whereHas('classification.parent', function ($q) use($business){
            $q->where('id_business', $business);
        })->where('account_code', '!=', $data->account_code)->get();
        
        foreach($code as $c){
            $array[] = $c->account_code;
        }
        
        $this->validate($request,[
            'edit_codeAccount' => Rule::notIn($array),
        ],
        [
            'edit_codeAccount.not_in' => 'Kode akun tidak boleh sama',
        ]);

        $data->id_classification = $request->edit_classificationAccount;
        $data->account_code = $request->edit_codeAccount;
        $data->account_name = $request->edit_nameAccount;
        $data->position = $request->edit_positionAccount;
        $data->save();

        return redirect()->route('akun.index')->with('success','Berhasil Mengubah Akun!');
    }

    public function destroy($id)
    {
        Account::findOrFail($id)->delete($id);

        return response()->json([
            'success' => 'Record deleted sucessfully'
        ]);
    }
}
