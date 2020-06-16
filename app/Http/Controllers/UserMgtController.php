<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Companies;

class UserMgtController extends Controller
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
        $companies = Companies::with('user')->get();

        return view('admin.user', compact('companies'));
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
        $datas = Companies::with('user')->where('id',$id)->get();

        return response()->json($datas);
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
        $data = Companies::where('id',$id)->first();
        $data->is_actived = $request->status;
        $data->save();

        return redirect()->route('admin.user.index')->with('toast_success','Berhasil Mengubah Data!');
    }

    public function changeStatus($id)
    {
        $data = Companies::where('id',$id)->first();
        $status = $data->is_actived == 1? 0 : 1;
        $data->is_actived = $status;
        $data->save();

        return response()->json(['success'=>'berhasil dibah']);
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
