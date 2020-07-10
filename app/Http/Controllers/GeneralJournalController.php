<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Companies;
use App\Business;
use App\Employee;
use App\GeneralJournal;
use App\Account;
use App\DetailJournal;
use App\InitialBalance;
use Auth;
use Redirect;

class GeneralJournalController extends Controller
{

    public function __construct()
    {
        $this->middleware(['role:company|employee']);
        $this->middleware('auth');
    }
    
    public function index()
    {
        if(isset($_GET['year']) || isset($_GET['month']) || isset($_GET['day'])){
            if (isset($_GET['year'], $_GET['month'], $_GET['day'])) {
                $year = $_GET['year'];
                $month = $_GET['month'];
                $day = $_GET['day'];
            } elseif(isset($_GET['year'], $_GET['month'])){
                $year = $_GET['year'];
                $month = $_GET['month'];
                $day = null;
            } elseif(isset($_GET['year'])){
                $year = $_GET['year'];
                $month = null;
                $day = null;
            }
        } else {
            $year = date('Y');
            $month = null;
            $day = null;
        }
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
        } else{
            $getBusiness = Employee::with('business')->where('id_user', $user->id)->first();
            $session = $getBusiness->id_business;
        }

        $account = Account::whereHas('classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->get();
        
        $data = DetailJournal::with('journal.account')
        ->whereHas('journal.account.classification.parent', function($q) use($session){
            $q->where('id_business', $session);
        })->whereYear('date', $year)->orderBy('date', 'DESC')->get();
        
        if ($month) {
            $data = DetailJournal::with('journal.account')
            ->whereHas('journal.account.classification.parent', function($q) use($session){
                $q->where('id_business', $session);
            })->whereYear('date', $year)->whereMonth('date', $month)->orderBy('date', 'DESC')->get();
        }
        if ($day) {
            $data = DetailJournal::with('journal.account')
            ->whereHas('journal.account.classification.parent', function($q) use($session){
                $q->where('id_business', $session);
            })->whereYear('date', $year)->whereMonth('date', $month)->whereDay('date', $day)->orderBy('date', 'DESC')->get();
        }
        $years = DetailJournal::selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();
        
        return view('user.jurnalUmum', compact('business', 'data','years', 'session', 'account', 'getBusiness'));
    }

    public function create()
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
        } else {
            $getBusiness = Employee::with('business')->where('id_user', $user->id)->first();
            $session = $getBusiness->id_business;
        }

        $account = Account::whereHas('classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->get();
        
        return view('user.tambahJurnal', compact('account', 'session', 'business', 'getBusiness'));
    }

    public function test(Request $request){
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
        } else {
            $getBusiness = Employee::with('business')->where('id_user', $user->id)->first();
            $session = $getBusiness->id_business;
        }

        $id = $request->account;
        
        $initial_balance = InitialBalance::with('account.classification.parent')
        ->whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->whereHas('account', function($q) use ($id){
            $q->where('id_account', $id);
        })->whereYear('date', $request->date)->get();
        
        return response()->json($initial_balance);
    }

    public function store(Request $request)
    {
        $debit = InitialBalance::with('account')->where('id_account', $request->id_debit_account)
        ->whereYear('date', $request->date)->first();
        
        $kredit = InitialBalance::with('account')->where('id_account', $request->id_credit_account)
        ->whereYear('date', $request->date)->first();
        
        $amount = $request->amount;
        $convert_amount = preg_replace("/[^0-9]/", "", $amount);
        
        if(!$debit){
            $saldo_debit = 0;
            $debit = Account::where('id', $request->id_debit_account)->first();
            if($debit->position == "Kredit"){
                if($convert_amount > $saldo_debit){
                    return Redirect::back()->withInput()->withError('insufficient');
                }
            }
        } else {
            $saldo_debit = $debit->amount;
            if($debit->account->position == "Kredit"){
                if($convert_amount > $saldo_debit){
                    return Redirect::back()->withInput()->withError('insufficient');
                }
            }
        }
        if(!$kredit){
            $saldo_kredit = 0;
            $kredit = Account::where('id', $request->id_credit_account)->first();
            if($kredit->position == "Debit"){
                if($convert_amount > $saldo_kredit){
                    return Redirect::back()->withInput()->withError('insufficient');
                }
            }
        } else {
            $saldo_kredit = $kredit->amount;
            if($kredit->account->position == "Debit"){
                if($convert_amount > $saldo_kredit){
                    return Redirect::back()->withInput()->withError('insufficient');
                }
            }
        }

        $this->validate($request,[
            'id_debit_account' => 'different:id_credit_account',
            'id_credit_account' => 'different:id_debit_account',
        ],
        [
            'id_debit_account.different' => 'Akun debit dan kredit tidak boleh sama',
            'id_credit_account.different' => 'Akun debit dan kredit tidak boleh sama'
        ]);

        $detail = new DetailJournal();
        $detail->receipt = $request->receipt;
        $detail->description = $request->description;
        $detail->amount = $convert_amount;
        $detail->date = $request->date;
        $detail->save();

        $kredit = new GeneralJournal();
        $kredit->id_detail = $detail->id;
        $kredit->id_account = $request->id_debit_account;
        $kredit->position = "Debit";
        $kredit->save();
        
        $debit = new GeneralJournal();
        $debit->id_detail = $detail->id;
        $debit->id_account = $request->id_credit_account;
        $debit->position = "Kredit";
        $debit->save();

        return redirect()->route('jurnal_umum.index')->with('success','Jurnal Ditambahkan!');
    }

    public function detailJournal(Request $request){
        $data = DetailJournal::with('journal.account')->where('id', $request->id)
        ->get();

        return response()->json($data);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request)
    {
        $debit = InitialBalance::with('account')->where('id_account', $request->id_debit_account)
        ->whereYear('date', $request->date)->first();
        
        $kredit = InitialBalance::with('account')->where('id_account', $request->id_credit_account)
        ->whereYear('date', $request->date)->first();
        
        $amount = $request->amount;
        $convert_amount = preg_replace("/[^0-9]/", "", $amount);
        
        if(!$debit){
            $saldo_debit = 0;
            $debit = Account::where('id', $request->id_debit_account)->first();
            if($debit->position == "Kredit"){
                if($convert_amount > $saldo_debit){
                    return Redirect::back()->withInput()->withError('insufficient');
                }
            }
        } else {
            $saldo_debit = $debit->amount;
            if($debit->account->position == "Kredit"){
                if($convert_amount > $saldo_debit){
                    return Redirect::back()->withInput()->withError('insufficient');
                }
            }
        }
        if(!$kredit){
            $saldo_kredit = 0;
            $kredit = Account::where('id', $request->id_debit_account)->first();
            if($kredit->position == "Debit"){
                if($convert_amount > $saldo_kredit){
                    return Redirect::back()->withInput()->withError('insufficient');
                }
            }
        } else {
            $saldo_kredit = $kredit->amount;
            if($kredit->account->position == "Debit"){
                if($convert_amount > $saldo_kredit){
                    return Redirect::back()->withInput()->withError('insufficient');
                }
            }
        }

        $this->validate($request,[
            'id_debit_account' => 'different:id_credit_account',
            'id_credit_account' => 'different:id_debit_account',
        ],
        [
            'id_debit_account.different' => 'Akun debit dan kredit tidak boleh sama',
            'id_credit_account.different' => 'Akun debit dan kredit tidak boleh sama'
        ]);

        $detail = DetailJournal::findOrFail($request->id_detail);
        $detail->receipt = $request->receipt;
        $detail->description = $request->description;
        $detail->amount = $convert_amount;
        $detail->date = $request->date;
        $detail->save();

        $kredit = GeneralJournal::findOrFail($request->id_credit);
        $kredit->id_account = $request->id_credit_account;
        $kredit->position = "Kredit";
        $kredit->save();

        $kredit = GeneralJournal::findOrFail($request->id_debit);
        $kredit->id_account = $request->id_debit_account;
        $kredit->position = "Debit";
        $kredit->save();

        return redirect()->route('jurnal_umum.index')->with('success','Berhasil Mengubah Jurnal!');
            
    }

    public function destroy($id)
    {
        DetailJournal::find($id)->delete($id);

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }
}
