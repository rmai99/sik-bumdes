<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\AccountClassification;
use App\AccountParent;

class ClassificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:company|employee']);

        $this->middleware('auth');
        
    }
    
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
        
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $business = AccountParent::where('id',$request->input_parent)->first()->id_business;
        
        $data = AccountClassification::whereHas('parent', function ($q) use($business){
            $q->where('id_business', $business);
        })->select('classification_code')->get();

        foreach($data as $d){
            $array[] = $d->classification_code;
        }
        
        $this->validate($request,[
            'input_code' => Rule::notIn($array),
        ],
        [
            'input_code.not_in' => 'Kode klasifikasi tidak boleh sama',
        ]);

        $data = new AccountClassification;
        $data->id_parent = $request->input_parent;
        $data->classification_code = $request->input_code;
        $data->classification_name = $request->input_name;
        $data->save();

        return redirect()->action('AccountController@index')->with('success','Berhasil Menambahkan Klasifikasi!');
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
        $business = AccountParent::where('id',$request->edit_parent)->first()->id_business;

        $data = AccountClassification::where('id', $id)->first();

        $code = AccountClassification::whereHas('parent', function ($q) use($business){
            $q->where('id_business', $business);
        })->where('classification_code', '!=', $data->classification_code)->get();
        
        foreach($code as $c){
            $array[] = $c->classification_code;
        }
        
        $this->validate($request,[
            'edit_code' => Rule::notIn($array),
        ],
        [
            'edit_code.not_in' => 'Kode klasifikasi tidak boleh sama',
        ]);
        
        $data->id_parent = $request->edit_parent;
        $data->classification_code = $request->edit_code;
        $data->classification_name = $request->edit_name;
        $data->save();

        return redirect()->route('akun.index')->with('success','Berhasil Mengubah Klasifikasi!');;
    }

    public function destroy($id)
    {
        AccountClassification::findOrFail($id)->delete($id);

        return response()->json([
            'success' => 'Record deleted sucessfully'
        ]);
    }
}
