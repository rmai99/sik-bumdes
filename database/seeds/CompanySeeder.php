<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\User;
use App\Companies;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($k = 0; $k <= 1; $k++){
            $faker = Faker::create('id_ID');

            $user = User::create([
                'email' => $faker->email,
                'password' => Hash::make('password'),
            ]);

            $user->assignRole('company');

            $uid = $user->id;

            $detail_user = Companies::create([
                'name'=> $faker->company,
                'address' => $faker->address,
                'phone_number' => $faker->phoneNumber,
                'id_user' => $uid,
                'created_at' => $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now'),
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
        }
    }
}
