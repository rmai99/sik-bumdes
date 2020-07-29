<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Companies;
use App\Business;
use App\Employee;
use App\BudgetPlan;
use App\AccountBudgetCategory;
use App\BudgetAccount;
use App\BudgetPlanRealization;
use Redirect;

class BudgetPlan_RealizationController extends Controller
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
            $company = $getBusiness->id_company;
            $session = $getBusiness->id_business;
        }
        if (isset($_GET['year'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }
        // dd($month);
        $account_plan = AccountBudgetCategory::with(['budget_account' => function ($query) use ($company) {
            $query->where('id_company', $company);
        },'budget_account.budget_plan' => function ($query) use ($month, $year) {
            $query->whereYear('date', $year);
            $query->whereMonth('date', $month);
        }])->get();
      
        $type = BudgetAccount::with(['budget_plan' => function ($query) use ($month, $year) {
            $query->whereYear('date', $year);
            $query->whereMonth('date', $month);
        }])->where('id_company', $company)->where('type','Belanja')->get();

        $account = BudgetAccount::where('id_company', $company)->get();

        $years = BudgetPlan::whereHas('budget_account', function($q) use ($company){
            $q->where('id_company', $company);
        })->selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();

        return view('user.rencanaAnggaranBisnis', compact('business', 'session', 'getBusiness', 'account_plan', 'account', 'type', 'years'));
    }

    public function store(Request $request)
    {
        $year = date("Y", strtotime($request->date));
        $account = BudgetPlan::where('id_budget_account', $request->id_budget_account)->whereYear('date', $year)->first();
        $validator = Validator::make($request->all(),[
            'id_budget_account' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ])->validate();
        // dd($account);
        if($account == null){
            $amount = $request->amount;
            $convert_amount = preg_replace("/[^0-9]/", "", $amount);

            $data = new BudgetPlan();
            $data->id_budget_account = $request->input('id_budget_account');
            $data->amount = $convert_amount;
            $data->date = $request->input('date');
            $data->save();
        } else {
            return Redirect::back()->withInput()->withErrors(['warning'=>'Anggaran Hanya Dapat Diinputkan Sekali dalam Sebulan']);
        }
        return redirect()->route('rencana_anggaran.index')->with('success','Anggaran Ditambahkan!');
    }

    public function detail(Request $request)
    {
        $data = BudgetPlan::where('id', $request->id)->get();
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $year = date("Y", strtotime($request->date));
        $account = BudgetPlan::where('id_budget_account', $request->id_budget_account)->whereYear('date', $year)->get();
        if(!$account){
            return Redirect::back()->withInput()->withErrors(['edit_warning'=>'Anggaran Hanya Dapat Diinputkan Sekali dalam Sebulan']);
        } else {
            $amount = $request->edit_amount;
            $convert_amount = preg_replace("/[^0-9]/", "", $amount);

            $data = BudgetPlan::findOrFail($id);
            $data->id_budget_account = $request->edit_budget_acount;
            $data->amount = $convert_amount;
            $data->date = $request->edit_date;
            $data->save();
        }
        return redirect()->route('rencana_anggaran.index')->with('success','Anggaran Ditambahkan!');
    }

    public function destroy($id)
    {
        BudgetPlan::find($id)->delete($id);

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }
    
    /**
     Realization Controller
     **/
    public function realization()
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
        if (isset($_GET['year'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }
        // dd($month);
        $account_plan = AccountBudgetCategory::with(['budget_account' => function ($query) use ($company) {
            $query->where('id_company', $company);
        },'budget_account.budget_plan' => function ($query) use ($month, $year) {
            $query->whereYear('date', $year);
            $query->whereMonth('date', $month);
        },'budget_account.budget_plan.realization'])->get();
      
        $type = BudgetAccount::with(['budget_plan' => function ($query) use ($month, $year) {
            $query->whereYear('date', $year);
            $query->whereMonth('date', $month);
        },'budget_plan.realization'])->where('id_company', $company)->where('type','Belanja')->get();

        $account = BudgetAccount::where('id_company', $company)->get();

        $years = BudgetPlan::whereHas('budget_account', function($q) use ($company){
            $q->where('id_company', $company);
        })->selectRaw('YEAR(date) as year')->orderBy('date', 'desc')->distinct()->get();

        return view('user.realisasiAnggaranBisnis', compact('business', 'session', 'getBusiness', 'account_plan', 'account', 'type', 'years'));
    }

    public function create_realization($year, $month){
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
        
        $plan = BudgetAccount::with(['budget_plan' => function ($query) use ($month, $year) {
            $query->whereYear('date', $year);
            $query->whereMonth('date', $month);
        },'budget_plan.realization'])->where('id_company', $company)->get();

        return view('user.tambahRealisasiAnggaranBisnis', compact('business', 'session', 'getBusiness', 'account_plan', 'plan', 'years'));
    }

    public function store_realization(Request $request){
        $count = 0 ;
        foreach($request->realisasi as $key => $value){
            if($request->realisasi[$key] != null){
                $amount = $request->realisasi[$key];
                $convert = preg_replace("/[^0-9]/", "", $amount);
                
                $realization = new BudgetPlanRealization();
                $realization->id_budget_plan = $request->id[$key];
                $realization->amount = $convert;
                $realization->save();
                $count++;
            }
        }
        if($count > 0){
            return redirect()->route('realisasi.show')->with('success','Realisasi Anggaran Berhasil Ditambahkan!');
        } else {
            return Redirect::back()->withInput()->withErrors(['null'=>'balance']);
        }
    }

    public function update_realization(Request $request){
        $data = BudgetPlanRealization::where('id_budget_plan', $request->id_plan)->first();
        $amount = $request->realisasi;
        $convert_amount = preg_replace("/[^0-9]/", "", $amount);
        
        $data->id_budget_plan = $request->id_plan;
        $data->amount = $convert_amount;
        $data->save();
        
        return redirect()->route('realisasi.show')->with('success','Realisasi Anggaran Berhasil Diubah!');
    }

    public function detail_realization(Request $request){
        $data = BudgetPlan::with(['realization','budget_account'])->where('id', $request->id)->get();
        return response()->json($data);
    }

    public function destroy_realization($id)
    {
        BudgetPlanRealization::find($id)->delete($id);

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
        
    }

}
