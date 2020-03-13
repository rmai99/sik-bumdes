<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Companies;
use DB;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('owner');

        $uid = $user->id;

        $detail_user = Companies::create([
            'name'=> $data['name'],
            'address' => $data['address'],
            'phone_number' => $data['phoneNumber'],
            'id_user' => $uid,
        ]);

        $uname = $detail_user->name;
        $detail_id = $detail_user->id;

        $usaha = DB::table('businesses')->insertGetId([
            'id_company' => $detail_id,
            'business_name' => $uname,
        ]);

        $parent_array = array('1' => 'Asset', '2' => 'Liabilitas', '3' => 'Ekuitas', '4' => 'Pendapatan', '5' => 'Beban', '6' => 'Pendapatan Lainnya', '7' => 'Biaya Lainnya');

        $class_array = array(
            array('11' => 'Aset Lancar', '12' => 'Aset Tetap', '13' => 'Aset Lainnya'),
            array('21' => 'Utang Lancar', '22' => 'Utang Jangka Panjang'),
            array('31' => 'Ekuitas'),
            array('41' => 'Pendapatan'),
            array('51' => 'Beban'),
            array('61' => 'Pendapatan Lainnya'),
            array('71' => 'Biaya Lainnya')
        );

        $akun_array = array(
			array("Kas" => array("1110"=>"Debit"), "Kas di Bank" => array("1111" => "Debit"), "Piutang Dagang" => array("1120" => "Debit"), "Sewa Dibayar Dimuka" => array("1130" => "Debit")),
			array("Tanah" => array("1210" => "Debit"), "Gedung" => array("1220" => "Debit"), "Akumulasi Penyusutan Gedung" => array("1220-1" => "Kredit"), "Kendaraan" => array("1230" => "Debit"), "Akumulasi Penyusutan Kendaraan" => array("1230-1" => "Kredit"), "Peralatan Kantor" => array("1240" => "Debit"), "Akumulasi Penyusutan Peralatan Kantor" => array("1240-1" => "Kredit")),
			array("Aset Lainnya" => array("1310" => "Debit")),
            array("Utang Dagang" => array("2110" => "Debit"), "Utang Gaji" => array("2120"=>"Kredit"), "Utang Bank" => array("2130" => "Kredit")),
            array("Obligasi" => array("2210" => "Kredit")),
            array("Modal Disetor" => array("3100" => "Kredit"), "Saldo Laba Ditahan" => array("3110"=>"Kredit"), "Saldo Laba Tahun Berjalan" => array("3120" => "Kredit")),
            array("Pendapatan Wisata" => array("4110" => "Kredit"), "Pendapatan Homestay" => array("4120" => "Kredit"), "Pendapatan Resto" => array("4130" => "Kredit"), "Pendapatan Event" => array("4140" => "Kredit")),
            array("Biaya Gaji" => array("5110" => "Debit"), "Biaya Listrik, Air dan Telepon" => array("5120" => "Debit"), "Biaya Administrasi dan Umum" => array("5130" => "Debit"), "Biaya Pemasaran" => array("5140" => "Debit"), "Biaya Perlengkapan Kantor" => array("5150" => "Debit"), "Biaya Sewa" => array("5160" => "Debit"), "Biaya Asuransi" => array("5170" => "Debit"), "Biaya Penyusutan Gedung" => array("5180" => "Debit"), "Biaya Penyusutan Kendaraan" => array("5190" => "Debit"), "Biaya Penyusutan Peralatan Kantor" => array("5200" => "Debit")),
            array("Pendapatan Lain-Lain" => array("6110" => "Kredit")),
            array("Biaya Lain-Lain" => array("7110" => "Debit"))
        );

        $j=-1;	
        foreach ($parent_array as $code => $name) {
            $parent=DB::table('account_parent')->insertGetId([
                'id_business' => $usaha,
                'parent_code' => $code,
                'parent_name' => $name,
            ]);

            $i=$code-1;
            foreach ($class_array[$i] as $classification_code => $value) {
                $classification = DB::table('account_classifications')->insertGetId([
                    'id_parent' => $parent,
                    'classification_code' => $classification_code,
                    'classification_name' => $value,
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



        return $user;
    }
}
