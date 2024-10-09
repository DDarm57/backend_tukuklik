<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\Subdistrict;
use GuzzleHttp\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new Client();
        $request = $client->request('GET', 'https://dev.farizdotid.com/api/daerahindonesia/provinsi');
        $response = $request->getBody();
        $response = json_decode($response);
        foreach($response->provinsi as $provinsi) {
            Province::insert([
                'prov_id'       => $provinsi->id,
                'prov_name'     => $provinsi->nama,
            ]);
            $reqCity = $client->request('GET', 'https://dev.farizdotid.com/api/daerahindonesia/kota?id_provinsi='.$provinsi->id);
            $responseCity = json_decode($reqCity->getBody());
            foreach($responseCity->kota_kabupaten as $city) {
                City::insert([
                    'city_id'   => $city->id,
                    'city_name' => $city->nama,
                    'prov_id'   => $provinsi->id
                ]);
                $reqDistrict = $client->request('GET', 'https://dev.farizdotid.com/api/daerahindonesia/kecamatan?id_kota='.$city->id);
                $responseDistrict = json_decode($reqDistrict->getBody());
                foreach($responseDistrict->kecamatan as $district) {
                    District::insert([
                        'dis_id'    => $district->id,
                        'dis_name'  => $district->nama,
                        'city_id'   => $city->id
                    ]);
                    $reqSubDistrict = $client->request('GET', 'https://dev.farizdotid.com/api/daerahindonesia/kelurahan?id_kecamatan='.$district->id);
                    $responseSubDistrict = json_decode($reqSubDistrict->getBody());
                    foreach($responseSubDistrict->kelurahan as $subDistrict) {
                        Subdistrict::insert([
                            'subdis_id' => $subDistrict->id,
                            'subdis_name'   => $subDistrict->nama,
                            'dis_id'    => $district->id
                        ]);
                    }
                }
            }
        }
    }
}
