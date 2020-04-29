<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
use App\Companies;
use App\Business;
use App\Employee;
use App\Account;
use App\AccountParent;
use App\DetailJournal;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(['role:owner|employee']);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $year = date('Y');
        
        $user = Auth::user()->id;
        $role = Auth::user();
        $isOwner = $role->hasRole('owner');

        if($isOwner){
            $session = session('business');
            
            $company = Companies::where('id_user', $user)->first()->id;
            $business = Business::where('id_company', $company)->get();

            $business = Business::where('id_company', $company)->get();
            $getBusiness = Business::where('id_company', $company)->first();

            if($session == 0){
                $session = $getBusiness->id;
            }
        } else {
            $getBusiness = Employee::where('id_user', $user)->first()->id_business;

            $session = $getBusiness;
        }

        $account = Account::whereHas('classification.parent', function ($q) use ($session){
            $q->where('id_business', $session);
        })
        ->count();

        $transaction = DetailJournal::whereHas('journal.account.classification.parent', function ($q) use ($session){
            $q->where('id_business', $session);
        })->whereYear('date', $year)
        ->count();

        $data = DetailJournal::with('journal.account')
        ->whereHas('journal.account.classification.parent', function($q) use($session){
            $q->where('id_business', $session);
        })->whereYear('date', $year)
        ->orderBy('date', 'DESC')
        ->paginate(3);

        $years = DetailJournal::selectRaw('YEAR(date) as year')
        ->orderBy('date', 'desc')
        ->distinct()
        ->get();

        // $parent = AccountParent::with('classification.account')
        // ->where('id_business', $session)
        // ->where('parent_account', "Asset")->first();
        // dd($cash);

        $cash = Account::whereHas('classification.parent', function ($q) use ($session){
            $q->where('id_business', $session);
        })->where('account_name', 'kas')
        ->first();
        
        if($cash != null){
            $sum = 0;
            if(!$cash->initialBalance()->whereYear('date', $year)->first()){
                $sum = 0;
            } else {
                $sum = $cash->initialBalance()->whereYear('date', $year)->first()->amount;
            }

            if($cash->journal()->exists()){
                $jurnals = $cash->journal()->whereHas('detail', function($q) use($year){
                    $q->whereYear('date', $year);
                })->get();
                
                foreach($jurnals as $jurnal){
                    if($jurnal->position == "Debit"){
                        $sum += $jurnal->amount;
                    } else if($jurnal->position == "Kredit"){
                        $sum -= $jurnal->amount;
                    }
                }
            } else {
                if($cash->initialBalance()->whereYear('date', $year)->exists()){
                    $sum = $sum;
                } else {
                    $sum = 0;
                }
            }
        } else {
            $sum = 0;
        }
        if($sum >=1000000){
            $sum = round(($sum/1000000),1).' jt';
        }
        return view('user/dashboard', compact('business', 'session', 'account', 'transaction', 'data', 'year', 'years', 'sum'));
    }

    public function getMonthly($year = null){
        if($year == null){
            $year = date('Y');
        }
        $month = date("m", strtotime("-1 month"));
        // dd($month); tahu itu bulat bulat itu 1
        
        
    
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

        $parent = AccountParent::with('classification.account')
        ->where('id_business', $session)->get();

        $biaya = 0;
        $pendapatan = 0;
        $biayalain = 0;
        $pendapatanlain = 0;

        foreach($parent as $p){
            $i = 0;
            $classification = $p->classification()->get();
            foreach($classification as $c){
                $account = $c->account()->get();
                foreach($account as $a){
                    $position = $a->position;

                    if(!$a->initialBalance()->whereYear('date', $year)->first()){
                        $saldo_awal = 0;
                    } else {
                        $saldo_awal = $a->initialBalance()->whereYear('date', $year)->first()->amount;
                    }

                    if($a->journal()->exists()){
                        $saldo_akhir = $saldo_awal;
                        $jurnals = $a->journal()->whereHas('detail', function($q) use($year, $month){
                            $q->whereYear('date', $year);
                        })->get();
                        foreach($jurnals as $jurnal){
                            if ($jurnal->position == $position) {
                                $saldo_akhir += $jurnal->amount;
                            }else {
                                $saldo_akhir -= $jurnal->amount;
                            }
                        }
                    } else {
                        if($a->initialBalance()->whereYear('date', $year)->first()){
                            $saldo_akhir = $saldo_awal;
                            
                        } else {
                            $saldo_akhir = 0;
                        }
                    }
                    
                    if($p->parent_name == "Pendapatan"){
                        $pendapatan += $saldo_akhir;
                        
                    } 
                    else if($p->parent_name == "Beban"){
                        $biaya += $saldo_akhir;
                    
                    }
                    else if($p->parent_name == "Pendapatan Lainnya"){
                        $pendapatanlain += $saldo_akhir;
                    }
                    else if($p->parent_name == "Biaya Lainnya"){
                        $biayalain += $saldo_akhir;
                    }

                }
                $i++;
            }
        }

        $income = $pendapatan - $biaya + $pendapatanlain - $biayalain;
        dd($income);

        return response()->json([
            'status'=>'success',
            'year'=>$year,
            'income'=>$income
          ]);
        
    }

    public function get_monthly_cash_flow($year = null){
        
        if(isset($_GET['year'])){
            $year = $_GET['year'];
             
        } else {
            $year = date('Y');
        }

        $user = Auth::user()->id;
        $role = Auth::user();
        $isOwner = $role->hasRole('owner');

        if($isOwner){
            $session = session('business');
            
            $company = Companies::where('id_user', $user)->first()->id;
            $business = Business::where('id_company', $company)->get();

            $business = Business::where('id_company', $company)->get();
            $getBusiness = Business::where('id_company', $company)->first();

            if($session == 0){
                $session = $getBusiness->id;
            }
        } else {
            $getBusiness = Employee::where('id_user', $user)->first()->id_business;

            $session = $getBusiness;
        }
        
        $account = Account::whereHas('classification.parent', function ($q) use ($session){
            $q->where('id_business', $session);
        })->where('account_name', 'kas')
        ->first();
        
        $hmm = array();
        $cash1 = array();
        $cash2 = array();
        $position = $account->position;
        
        $monthGroup = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        foreach ($monthGroup as $month) {
            $cash_out = 0;
            $cash_in = 0;
            if(!$account->initialBalance()->whereYear('date', $year)->whereMonth('date', $month)->first()){
                $cash_in = 0;
            } else {
                $cash_in = $account->initialBalance()->whereYear('date', $year)->whereMonth('date', $month)->first()->amount;
            }
            if($account->journal()->exists()){
                $jurnals = $account->journal()->whereHas('detail', function($q) use($year, $month){
                    $q->whereYear('date', $year)->whereMonth('date', $month);
                })->get();
                
                foreach($jurnals as $jurnal){
                    if($jurnal->position == "Debit"){
                        $cash_in += $jurnal->amount;
                    } else if($jurnal->position == "Kredit"){
                        $cash_out += $jurnal->amount;
                    }
                }
            } else {
                if($account->initialBalance()->whereYear('date', $year)->first()){
                    $cash_in = $cash_in;
                } else {
                    $cash_out = 0;
                    $cash_in = 0;
                }
            }

            
            $thisMonth[] = $month;
            $cash1[] = $cash_in;
            $cash2[] = -$cash_out;

        }
        return response()->json([
            'status'=>'success',
            'bulan'=>$thisMonth,
            'in'=>$cash1,
            'out'=>$cash2,
          ]);
    }

    public function get_daily_cash_flow($date = null){
        
        if($date == null){
            $date = date('Y-m');
            $time = strtotime("now");

            $end = date("Y-m", strtotime("+1 month", $time));

            $begin = new \DateTime( $date.'-01' );
            $end = new \DateTime( $end.'-01' );
             
        } else {
            $time = strtotime($date);

            $end = date("Y-m", strtotime("+1 month", $time));

            $begin = new \DateTime( $date.'-01' );
            $end = new \DateTime( $end.'-01' );
        }

        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval ,$end);

        $month = date("m", strtotime("first day of this month", $time));
        $year = date("Y", strtotime("first day of this month", $time));

        $day = array();
        $value = array();

        $user = Auth::user()->id;
        $role = Auth::user();
        $isOwner = $role->hasRole('owner');

        if($isOwner){
            $session = session('business');
            
            $company = Companies::where('id_user', $user)->first()->id;
            $business = Business::where('id_company', $company)->get();

            $business = Business::where('id_company', $company)->get();
            $getBusiness = Business::where('id_company', $company)->first();

            if($session == 0){
                $session = $getBusiness->id;
            }
        } else {
            $getBusiness = Employee::where('id_user', $user)->first()->id_business;

            $session = $getBusiness;
        }
        
        $account = Account::whereHas('classification.parent', function ($q) use ($session){
            $q->where('id_business', $session);
        })->where('account_name', 'Kas')
        ->first();
        
        $position = $account->position;
        foreach ($daterange as $date) {
            $cash_out = 0;
            $cash_in = 0;
            if(!$account->initialBalance()->whereDate('date', $date)->first()){
                $cash_in = 0;
            } else {
                $cash_in = $account->initialBalance()->whereDate('date', $date)->first()->amount;
            }
            if($account->journal()->exists()){
                $jurnals = $account->journal()->whereHas('detail', function($q) use($date){
                    $q->whereDate('date', $date);
                })->get();
                
                foreach($jurnals as $jurnal){
                    if($jurnal->position == "Debit"){
                        $cash_in += $jurnal->amount;
                    } else if($jurnal->position == "Kredit"){
                        $cash_out += $jurnal->amount;
                    }
                }
            } else {
                if($account->initialBalance()->whereYear('date', $year)->first()){
                    $cash_in = $cash_in;
                } else {
                    $cash_out = 0;
                    $cash_in = 0;
                }
            }

            
            $day[] = $date->format("j");
            $cash1[] = $cash_in;
            $cash2[] = $cash_out;

        }
        return response()->json([
            'status'=>'success',
            'day'=>$day,
            'in'=>$cash1,
            'out'=>$cash2,
          ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $year = date('Y');
        
        $user = Auth::user()->id;
        $role = Auth::user();
        $isOwner = $role->hasRole('owner');

        if($isOwner){
            $session = session('business');
            
            $company = Companies::where('id_user', $user)->first()->id;
            $business = Business::where('id_company', $company)->get();

            $business = Business::where('id_company', $company)->get();
            $getBusiness = Business::where('id_company', $company)->first();

            if($session == 0){
                $session = $getBusiness->id;
            }
        } else {
            $getBusiness = Employee::where('id_user', $user)->first()->id_business;

            $session = $getBusiness;
        }
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
    public function update(Request $request, $id)
    {
        //
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
