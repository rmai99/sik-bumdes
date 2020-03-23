<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
use App\Companies;
use App\Business;
use App\Employee;
use App\Account;
use App\DetailJournal;

class DashboardController extends Controller
{
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

        return view('user/dashboard', compact('business', 'session', 'account', 'transaction'));
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
