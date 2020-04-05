<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Companies;
use App\Business;
use App\Employee;
use App\GeneralJournal;
use App\Account;
use App\DetailJournal;
use Auth;

class GeneralJournalController extends Controller
{
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
        } else{
            $getBusiness = Employee::where('id_user', $user)->first()->id;

            $session = $getBusiness;
        }
        $account = Account::whereHas('classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->get();

        $data = DetailJournal::with('journal.account')
        ->whereHas('journal.account.classification.parent', function($q) use($session){
            $q->where('id_business', $session);
        })->whereYear('date', $year)
        ->get();
        
        
        if ($month) {
            $data = DetailJournal::with('journal.account')
            ->whereHas('journal.account.classification.parent', function($q) use($session){
                $q->where('id_business', $session);
            })->whereYear('date', $year)->whereMonth('date', $month)
            ->get();
        }
        if ($day) {
            $data = DetailJournal::with('journal.account')
            ->whereHas('journal.account.classification.parent', function($q) use($session){
                $q->where('id_business', $session);
            })->whereYear('date', $year)->whereMonth('date', $month)->whereDay('date', $day)
            ->get();
        }

        $years = DetailJournal::selectRaw('YEAR(date) as year')
        ->orderBy('date', 'desc')
        ->distinct()
        ->get();
        // 99dd($years);
        
        return view('user.jurnalUmum', compact('business', 'data','years', 'session', 'account'));
    }

    public function create()
    {
        $user = Auth::user()->id;

        $role = Auth::user();
        $isOwner = $role->hasRole('owner');

        if($isOwner){
        $session = session('business');

        $company = Companies::where('id_user', $user)->first()->id;
        $business = Business::where('id_company', $company)->get();
        $getBusiness = Business::where('id_company', $company)->first()->id;

            if($session == 0){
                $session = $getBusiness;
            }

        } else {
            $getBusiness = Employee::where('id_user', $user)->select('id_business')->first()->id_business;
            $session = $getBusiness;
        }

        $account = Account::whereHas('classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->get();
        // dd($account);
        
        return view('user.tambahJurnal', compact('account', 'session', 'business'));
    }

    public function store(Request $request)
    {
        foreach($request->receipt as $key => $value){
            $detail = new DetailJournal();
            $detail->receipt = $request->receipt[$key];
            $detail->description = $request->description[$key];
            $detail->date = $request->date[$key];
            $detail->save();

            $kredit = new GeneralJournal();
            $kredit->id_detail = $detail->id;
            $kredit->id_account = $request->id_credit_account[$key];
            $kredit->position = "Kredit";
            $kredit->amount = $request->credit[$key];
            $kredit->save();
    
            $kredit = new GeneralJournal();
            $kredit->id_detail = $detail->id;
            $kredit->id_account = $request->id_debit_account[$key];
            $kredit->position = "Debit";
            $kredit->amount = $request->debit[$key];
            $kredit->save();
        }

        return redirect()->route('jurnal_umum.index')->with('success','Berhasil Menambah Jurnal!');;
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
            
        $detail = DetailJournal::findOrFail($request->id_detail);
        $detail->receipt = $request->receipt;
        $detail->description = $request->description;
        $detail->date = $request->date;
        $detail->save();

        $kredit = GeneralJournal::findOrFail($request->id_credit);
        $kredit->id_account = $request->id_credit_account;
        $kredit->position = "Kredit";
        $kredit->amount = $request->credit;
        $kredit->save();

        $kredit = GeneralJournal::findOrFail($request->id_debit);
        $kredit->id_account = $request->id_debit_account;
        $kredit->position = "Debit";
        $kredit->amount = $request->debit;
        $kredit->save();

        return redirect()->route('jurnal_umum.index')->with('success','Berhasil Mengubah Jurnal!');
            
    }

    public function destroy($id)
    {
        $detail = DetailJournal::findOrFail($id);
        $detail->delete();

        return redirect()->route('jurnal_umum.index')->with('success','Berhasil Mengahapus data!');;
    }
}
