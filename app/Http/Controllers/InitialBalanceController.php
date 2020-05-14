<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
use App\Companies;
use App\Business;
use App\AccountParent;
use App\InitialBalance;
use App\Employee;

class InitialBalanceController extends Controller
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
        $role = Auth::user();
        $isCompany = $role->hasRole('company');
        
        $user = Auth::user()->id;

        if($isCompany){
            $session = session('business');
            $company = Companies::where('id_user', $user)->first()->id;
            $business = Business::where('id_company', $company)->get();
            if($session == null){
                $session = Business::where('id_company', $company)->first()->id;
            }
            $getBusiness = Business::with('company')
            ->where('id_company', $company)
            ->where('id', $session)->first();
        } else {
            $getBusiness = Employee::with('business')->where('id_user', $user)->first();
            $session = $getBusiness->id_business;
        }

        if (isset($_GET['year'])) {
            $year = $_GET['year'];
        } else {
            $year = date('Y');
        }
        $account_parent = AccountParent::with('classification.account')
        ->where('id_business', $session)
        ->get();
        // dd($account_parent);

        $initial_balance = InitialBalance::with('account.classification.parent')
        ->whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->whereYear('date', $year)
        ->get();
        
        $years = InitialBalance::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->selectRaw('YEAR(date) as year')
        ->orderBy('date', 'desc')
        ->distinct()
        ->get();

        return view('user.neracaAwal',compact('initial_balance','account_parent','years','business', 'year', 'session', 'getBusiness'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $dates = date('Y', strtotime($request->date) );
        $data = InitialBalance::where('id_account', $request->id_account)->whereYear('date','=', $dates)->first();
        if($data){
            $dates = date('Y-m-d', strtotime($data->date . " +1 year") );
        }else{
            $dates = 0000-00-00;
        }
        
        $this->validate($request,[
            'id_account' => 'required',
            'amount' => 'required',
            'date' => 'required|after_or_equal:'.$dates,
        ]);

        $amount = $request->amount;
        $convert_amount = preg_replace("/[^0-9]/", "", $amount);

        $data = new InitialBalance();
        $data->date = $request->date;
        $data->id_account = $request->id_account;
        $data->amount = $convert_amount;
        $data->save();

        return redirect()->route('neraca_awal.index')->with('success','Berhasil Menambahkan Data!');
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
        $data = InitialBalance::where('id', $id)->first();
        $db = date('Y', strtotime($data->date)); //ada di db
        $dates = date('Y', strtotime($request->edit_date)); //direquest
        $date = InitialBalance::where('id_account', $request->edit_acount)->whereYear('date','=', $dates)->first();
        if($db == $dates){
            $dates = 0000-00-00;
        } else {
            if($date){
                $dates = date('Y-m-d', strtotime($date->date . " +1 year") );
            } else {
                $dates = 0000-00-00;
            }
        }
        
        $this->validate($request,[
            'edit_date' => 'required|after_or_equal:'.$dates,
        ]);

        $amount = $request->edit_amount;
        $convert_amount = preg_replace("/[^0-9]/", "", $amount);
        
        $data->id_account = $request->edit_acount;
        $data->amount = $convert_amount;
        $data->date = $request->edit_date;
        $data->save();
        
        return redirect()->route('neraca_awal.index')->with('success','Berhasil Mengubah Data!');
    }

    public function destroy($id)
    {
        InitialBalance::find($id)->delete($id);

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }

    public function detailBalance(Request $request)
    {
        $data = InitialBalance::where('id', $request->id)
        ->get();

        return response()->json($data);
    }
}
