<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\User;
use App\Companies;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:super admin|admin']);

        $this->middleware('auth');
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Companies::count();
        $pro = Companies::where('is_actived', 1)->count();
        $reguler = Companies::where('is_actived', 0)->count();
        $admin = User::role('admin')->count();

        $years = Companies::selectRaw('YEAR(created_at) as year')->orderBy('created_at', 'desc')
        ->distinct()->get();

        return view('admin/dashboard', compact('companies', 'pro', 'reguler', 'admin', 'years'));
    }

    public function user_register(){
        
        if(isset($_GET['year'])){
            $year = $_GET['year'];
             
        } else {
            $year = date('Y');
        }
        
        $monthGroup = ['01','02','03','04','05','06','07','08','09','10','11','12'];

        foreach ($monthGroup as $month){
            $companies = Companies::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
            
            $thisMonth[] = $month;
            $total[] = $companies;
        }

        return response()->json([
            'status'=>'success',
            'bulan'=>$thisMonth,
            'in'=>$total,
          ]);
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
