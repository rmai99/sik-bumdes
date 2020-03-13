<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountClassification;

class ClassificationController extends Controller
{
    public function detailClassification(Request $request)
    {
        $classification = AccountClassification::where('id', $request->id)
        ->get();

        return response()->json($classification);
    }

    public function findClassification(Request $request)
    {
        $classification = AccountClassification::where('id_parent', $request->id)
        ->get();

        return response()->json($classification);
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // $data = AccountClassification::where('account_code', $request->name)->get();

        // $this->validate($request,[
        //     'id_parent' => 'required',
        //     'classification_code' => 'required',
        //     'classification_name' => 'required|after_or_equal:'.$dates,
        // ]);

        $data = new AccountClassification;
        $data->id_parent = $request->parent;
        $data->classification_code = $request->code;
        $data->classification_name = $request->name;
        $data->save();

        return redirect()->action('AccountController@index')->with('success','Berhasil Menambahkan Data!');
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
        $data = AccountClassification::where('id', $id)->first();

        $data->id_parent = $request->parent;
        $data->classification_code = $request->code;
        $data->classification_name = $request->name;
        $data->save();

        return redirect()->route('akun.index')->with('success','Berhasil Mengubah Data!');;
    }

    public function destroy($id)
    {
        $data = AccountClassification::where('id',$id)->first();
        $data->delete();
        return redirect()->route('akun.index')->with('success','Akun berhasil di hapus');;
    }
}
