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
        // dd($request);
        $debit = 0;
        $credit = 0;
        foreach($request->account as $key => $value){
            $amount_debit = $request->amount_debit[$key];
            $convert_debit = preg_replace("/[^0-9]/", "", $amount_debit);
            $debit += (int)$convert_debit;

            $amount_credit = $request->amount_credits[$key];
            $convert_credit = preg_replace("/[^0-9]/", "", $amount_credit);
            $credit += (int)$convert_credit;
        }
        if($debit != $credit){
            return Redirect::back()->withInput()->withErrors(['balance'=>'balance']);
        }
        foreach($request->account as $key => $value){
            $account = InitialBalance::with('account')->where('id_account', $request->account[$key])
            ->whereYear('date', $request->date)->first(); 
            if($account == null){
                $account = Account::where('id', $request->account[$key])->first();
                if($account->position == "Debit"){
                    if($request->amount_credits[$key] != null){
                        return Redirect::back()->withInput()->withError('insufficient');
                    }
                }else if($account->position == "Kredit"){
                    if($request->amount_debit[$key] != null){
                        return Redirect::back()->withInput()->withError('insufficient');
                    }
                }
            }
        }
        $detail = new DetailJournal();
        $detail->receipt = $request->receipt;
        $detail->description = $request->description;
        $detail->date = $request->date;
        $detail->save();

        foreach($request->account as $key => $value){
            if($request->amount_debit[$key] != null){
                $amount = $request->amount_debit[$key];
                $convert = preg_replace("/[^0-9]/", "", $amount);

                $jurnal = new GeneralJournal();
                $jurnal->position = "Debit";
            } else if($request->amount_credits[$key] != null){
                $amount = $request->amount_credits[$key];
                $convert = preg_replace("/[^0-9]/", "", $amount);

                $jurnal = new GeneralJournal();
                $jurnal->position = "Kredit";
            }
            $jurnal->id_detail = $detail->id;
            $jurnal->id_account = $request->account[$key];
            $jurnal->amount = $convert;
            $jurnal->save();
        }

        return redirect()->route('jurnal_umum.index')->with('success','Jurnal Ditambahkan!');
    }

    public function detailJournal(Request $request){
        $data = DetailJournal::with('journal.account')->where('id', $request->id)
        ->get();

        return response()->json($data);
    }

    public function show($id)
    {
        
    }

    public function edit($id)
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
        } else{
            $getBusiness = Employee::with('business')->where('id_user', $user->id)->first();
            $session = $getBusiness->id_business;
        }

        $journal = DetailJournal::with('journal.account')->where('id', $id)
        ->first();
        // dd($journal);

        $account = Account::whereHas('classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->get();
        // return response()->json($journal);

        return view('user.editJurnal', compact('journal', 'account', 'getBusiness', 'business', 'session'));
    }

    public function update(Request $request)
    {
        $debit = 0;
        $credit = 0;
        foreach($request->account as $key => $value){
            $amount_debit = $request->amount_debit[$key];
            $convert_debit = preg_replace("/[^0-9]/", "", $amount_debit);
            $debit += (int)$convert_debit;

            $amount_credit = $request->amount_credits[$key];
            $convert_credit = preg_replace("/[^0-9]/", "", $amount_credit);
            $credit += (int)$convert_credit;
        }
        if($debit != $credit){
            return Redirect::back()->withInput()->withError('insufficient');
        }
        foreach($request->account as $key => $value){
            $account = InitialBalance::with('account')->where('id_account', $request->account[$key])
            ->whereYear('date', $request->date)->first(); 
            if($account == null){
                $account = Account::where('id', $request->account[$key])->first();
                if($account->position == "Debit"){
                    if($request->amount_credits[$key] != null){
                        return Redirect::back()->withInput()->withError('insufficient');
                    }
                }else if($account->position == "Kredit"){
                    if($request->amount_debit[$key] != null){
                        return Redirect::back()->withInput()->withError('insufficient');
                    }
                }
            }
        }
        $detail = DetailJournal::findOrFail($request->id_detail);
        $detail->receipt = $request->receipt;
        $detail->description = $request->description;
        $detail->date = $request->date;
        $detail->save();

        foreach($request->account as $key => $value){
            if($request->id_debit[$key] != null){
                if($request->amount_debit[$key] != null){
                    $amount = $request->amount_debit[$key];
                    $convert = preg_replace("/[^0-9]/", "", $amount);
    
                    $jurnal = GeneralJournal::findOrFail($request->id_debit[$key]);
                    $jurnal->position = "Debit";
                } else if($request->amount_credits[$key] != null){
                    $amount = $request->amount_credits[$key];
                    $convert = preg_replace("/[^0-9]/", "", $amount);
    
                    $jurnal = GeneralJournal::findOrFail($request->id_credit[$key]);
                    $jurnal->position = "Kredit";
                }
            } else {
                if($request->amount_debit[$key] != null){
                    $amount = $request->amount_debit[$key];
                    $convert = preg_replace("/[^0-9]/", "", $amount);
    
                    $jurnal = new GeneralJournal();
                    $jurnal->id_detail = $detail->id;
                    $jurnal->position = "Debit";
                } else if($request->amount_credits[$key] != null){
                    $amount = $request->amount_credits[$key];
                    $convert = preg_replace("/[^0-9]/", "", $amount);
    
                    $jurnal = new GeneralJournal();
                    $jurnal->id_detail = $detail->id;
                    $jurnal->position = "Kredit";
                }
            }
            
            $jurnal->id_account = $request->account[$key];
            $jurnal->amount = $convert;
            $jurnal->save();
        }

        return redirect()->route('jurnal_umum.index')->with('success','Jurnal Berhasil Diedit!');
    }

    public function destroy($id)
    {
        DetailJournal::find($id)->delete($id);

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }

    public function destroyJournal($id)
    {
        GeneralJournal::find($id)->delete($id);

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }
}
