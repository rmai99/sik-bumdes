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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = Auth::user();
        $isOwner = $role->hasRole('owner');
        
        $user = Auth::user()->id;

        if($isOwner){
            $session = session('business');

            $companies = Companies::where('id_user', $user)->first();
            $company = $companies->id;
            
            $business = Business::where('id_company', $company)->get();

            $getBusiness = Business::where('id_company', $company)->first()->id;
            
            if($session == 0){
                $session = $getBusiness;
            }

        } else {
            $getBusiness = Employee::where('id_user', $user)->select('id_business')->first()->id_business;
            
            $session = $getBusiness;
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

        return view('user.neracaAwal',compact('initial_balance','account_parent','years','business', 'year', 'session'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = InitialBalance::where('id_account', $request->id_account)->first();
        

        if($data){
            $dates = date('Y-m-d', strtotime($data->date . " +1 year") );
        } else {
            $dates = 0000-00-00;
        }
        
        $this->validate($request,[
            'id_account' => 'required',
            'amount' => 'required',
            'date' => 'required|after_or_equal:'.$dates,
        ]);

        $data = new InitialBalance();
        $data->date = $request->date;
        $data->id_account = $request->id_account;
        $data->amount = $request->amount;
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
        $data = InitialBalance::where('id_account', $request->id_account)->first();

        if($data){
            $dates = date('Y-m-d', strtotime($data->date . " +1 year") );
        } else {
            $dates = 0000-00-00;
        }
        
        $this->validate($request,[
            'id_account' => 'required',
            'amount' => 'required',
            'date' => 'required|after_or_equal:'.$dates,
        ]);

        $data = InitialBalance::where('id', $id)->first();

        $data->id_account = $request->id_account;
        $data->amount = $request->amount;
        $data->date = $request->date;
        $data->save();
        
        return redirect()->route('neraca_awal.index')->with('success','Berhasil Mengubah Data!');
    }

    public function destroy($id)
    {
        $data = InitialBalance::where('id', $id)->first();
        $data->delete();

        return redirect()->route('neraca_awal.index')->with('success','Berhasil Menghapus Data!');;
    }

    public function detailBalance(Request $request)
    {
        $data = InitialBalance::where('id', $request->id)
        ->get();

        return response()->json($data);
    }
}
