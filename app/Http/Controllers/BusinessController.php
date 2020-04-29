<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
use App\Companies;
use App\Business;
use DB;


class BusinessController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:owner|employee']);

        $this->middleware('auth');
        
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->id;

        $session = session('business');
        
        $company = Companies::where('id_user', $user)->first()->id;
        
        $business = Business::where('id_company', $company)->get();
        
        return view('user.bisnis', compact('business', 'session'));
    }

    public function setBusiness($id){
        session(['business' => $id]);

        return redirect()->route('dashboard.index');
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
        $user = Auth::user();

        $company = Companies::where('id_user', $user->id)->first()->id;
        
        $data = new Business;
        $data->business_name = $request->business_name;
        $data->id_company = $company;
        $data->save();

        $parent_array = array('1' => 'Asset', '2' => 'Liabilitas', '3' => 'Ekuitas', '4' => 'Pendapatan', '5' => 'Beban', '6' => 'Pendapatan Lainnya', '7' => 'Biaya Lainnya');
            
            $class_array = array(
            array('11'=>'Aset Lancar', '12' => 'Aset Tetap', '13' => 'Aset Lainnya'), //ini buat anak anaknya aset
            array('21'=>'Utang Lancar', '22' => 'Utang Jangka Panjang'), //ini buat anak anaknya aset            
            array('31'=>'Ekuitas'),
            array('41'=>'Pendapatan'),
            array('51'=>'Beban'),
            array('61'=>'Pendapatan Lainnya'),
            array('71'=>'Biaya Lainnya')
            );
            $akun_array = array(
            array("kas"=>array("1110"=>"Debit"), "kas di bank"=> array("1111"=>"Debit"), "Piutang Dagang" => array("1120" => "Debit"), "Sewa Dibayar Dimuka"=>array("1130"=>"Debit")),
            array("Tanah"=>array("1210"=>"Debit"), "Gedung"=> array("1220"=>"Debit"), "Akumulasi Penyusutan Gedung" => array("1220-1"=>"Kredit"), "Kendaraan" => array("1230"=>"Debit"), "Akumulasi Penyusutan Kendaraan"=>array("1230-1"=>"Kredit"), "Peralatan Kantor"=>array("1240"=> "Debit"), "Akumulasi Penyusutan Peralatan Kantor"=>array("1240-1"=>"Kredit")),
            array("Aset Lainnya"=>array("1310"=>"Debit")),
            array("Utang Dagang"=>array("2110"=>"Kredit"), "Utang Gaji"=>array("2120"=>"Kredit"), "Utang Bank"=>array("2130"=>"Kredit")),
            array("Obligasi"=>array("2210"=>"Kredit")),
            array("Modal Disetor"=>array("3100"=>"Kredit"), "Saldo Laba Ditahan"=> array("3110"=>"Kredit"), "Saldo Laba Tahun Berjalan"=>array("3120"=>"Kredit")),
            array("Pendapatan Wisata"=> array("4110"=>"Kredit"), "Pendapatan Homestay"=>array("4120"=>"Kredit"), "Pendapatan Resto"=> array("4130"=>"Kredit"), "Pendapatan Event"=> array("4140"=>"Kredit")),
            array("Biaya Gaji"=>array("5110"=>"Debit"), "Biaya Listrik, Air dan Telepon"=>array("5120"=>"Debit"), "Biaya Administrasi dan Umum" =>array("5130"=>"Debit"), "Biaya Pemasaran"=>array("5140"=>"Debit"), "Biaya Perlengkapan Kantor"=>array("5150"=>"Debit"), "Biaya Sewa"=>array("5160"=>"Debit"), "Biaya Asuransi"=>array("5170"=>"Debit"), "Biaya Penyusutan Gedung"=>array("5180"=>"Debit"), "Biaya Penyusutan Kendaraan"=>array("5190"=>"Debit"), "Biaya Penyusutan Peralatan Kantor"=>array("5200"=>"Debit")),
            array("Pendapatan Lain-Lain"=>array("6110"=>"Kredit")),
            array("Biaya Lain-Lain"=>array("7110"=>"Debit"))
            );
        
        $j=-1;	
        foreach ($parent_array as $code => $name) {
            $parent=DB::table('account_parent')->insertGetId([
                'id_business' => $data->id,
                'parent_code' => $code,
                'parent_name' => $name,
            ]);

            $i = $code-1;
            foreach ($class_array[$i] as $classification_code => $value) {
                $classification = DB::table('account_classifications')->insertGetId([
                    'id_parent' => $parent,
                    'classification_name' => $value,
                    'classification_code' => $classification_code,
                ]);
                $j++;
                foreach ($akun_array[$j] as $account_name => $attribute) {
                    foreach ($attribute as $account_code => $position) {
                        $akun = DB::table('accounts')->insert([
                            'id_classification' => $classification,
                            'account_code' => $account_code,
                            'account_name' => $account_name,
                            'position' => $position,
                        ]);
                    }
                }					
            }
        }

        return redirect()->route('bisnis.index')->with('success','Berhasil Menambahkan Bisnis!');
    }

    public function detailBusiness(Request $request)
    {
        $business = Business::where('id', $request->id)
        ->get();

        return response()->json($business);
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
        
        $data = Business::where('id', $id)->first();

        $data->business_name = $request->name;
        
        $data->save();

        return redirect()->route('bisnis.index')->with('success','Berhasil Mengubah Data!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Business::findOrFail($id)->delete($id);
        
        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);

    }
}
