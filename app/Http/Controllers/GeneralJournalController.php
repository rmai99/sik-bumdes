<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Companies;
use App\Business;
use App\Employee;
use App\GeneralJournal;
use App\Account;
use App\Receipt;
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
        
        $data = GeneralJournal::with('cek','account.classification.parent')
        ->whereHas('account.classification.parent', function($q) use($session){
            $q->where('id_business', $session);
        })->whereYear('date', $year)
        ->get();
        
        if ($month) {
            $data = GeneralJournal::with('cek','account.classification.parent')
            ->whereHas('account.classification.parent', function($q) use($session){
                $q->where('id_business', $session);
            })->whereYear('date', $year)->whereMonth('date', $month)->get();
        }
        if ($day) {
            $data = GeneralJournal::with('cek','account.classification.parent')
            ->whereHas('account.classification.parent', function($q) use($session){
                $q->where('id_business', $session);
            })->whereYear('date', $year)->whereMonth('date', $month)->whereDay('date', $day)->get();
        }

        $years = GeneralJournal::whereHas('account.classification.parent', function($q) use ($session){
            $q->where('id_business', $session);
        })->selectRaw('YEAR(date) as year')
        ->orderBy('date', 'desc')
        ->distinct()
        ->get();
        
        return view('user.jurnalUmum', compact('business', 'data','years', 'session'));
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
            $kwitansi = new Receipt();
            $kwitansi->receipt = $request->receipt[$key];
            $kwitansi->date = $request->date[$key];
            $kwitansi->save();

            $kredit = new GeneralJournal();
            $kredit->description = $request->description[$key];
            $kredit->id_receipt = $kwitansi->id;
            $kredit->id_account = $request->id_credit_account[$key];
            $kredit->position = "Kredit";
            $kredit->amount = $request->credit[$key];
            $kredit->date = $request->date[$key];
            $kredit->save();
    
            $kredit = new GeneralJournal();
            $kredit->description = $request->description[$key];
            $kredit->id_receipt = $kwitansi->id;
            $kredit->id_account = $request->id_debit_account[$key];
            $kredit->position = "Debit";
            $kredit->amount = $request->debit[$key];
            $kredit->date = $request->date[$key];
            $kredit->save();
        }
        

        return redirect()->route('jurnal_umum.index')->with('success','Berhasil Menambah Jurnal!');;
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
        //
    }

    public function destroy($id)
    {
        //
    }
}
