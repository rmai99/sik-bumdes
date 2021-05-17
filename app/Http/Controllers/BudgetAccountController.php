<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
use DB;
use App\Companies;
use App\Business;
use App\Employee;
use App\BudgetAccount;
use App\AccountBudgetCategory;

class BudgetAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:company|employee']);
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
            $company = $getBusiness->id_company;
            $session = $getBusiness->id_business;
        }
        $account = AccountBudgetCategory::with(['budget_account' => function ($query) use ($company) {
              $query->where('id_company', $company);
          }])->get();
        
        $type = BudgetAccount::where('id_company', $company)->where('type','Belanja')->get();

        return view('user.akun_anggaran', compact('business', 'session', 'getBusiness', 'type', 'account'));
    }

    public function detail(Request $request)
    {
        $account = BudgetAccount::where('id', $request->id)->get();
        
        return response()->json($account);
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
        $user = Auth::user();
        $isCompany = $user->hasRole('company');
        if($isCompany){
            $session = session('business');
            $company = Companies::where('id_user', $user->id)->first()->id;
        } else {
            $getBusiness = Employee::with('business')->where('id_user', $user->id)->first();
            $company = $getBusiness->id_company;
        }
        $data = new BudgetAccount();
        $data->type = $request->type;
        $data->id_category = $request->kategori;
        $data->name = $request->namaAkunAnggaran;
        $data->id_company = $company;
        $data->save();

        return redirect()->route('akun_anggaran.index')->with('success','Berhasil Menambahkan Akun!');
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
        $data = BudgetAccount::where('id', $id)->first();
        $data->type = $request->type;
        $data->id_category = $request->kategori;
        $data->name = $request->namaAkunAnggaran;
        $data->save();

        return redirect()->route('akun_anggaran.index')->with('success','Berhasil Mengubah Akun Anggaran!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        BudgetAccount::find($id)->delete($id);

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }
}
