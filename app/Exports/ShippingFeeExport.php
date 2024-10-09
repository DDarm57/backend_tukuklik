<?php

namespace App\Exports;

use App\Models\ShippingFee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShippingFeeExport implements FromQuery, WithHeadings
{

    public function __construct()
    {
        
    }

    public function headings(): array
    {
        return [
            'Provinsi',
            'Kota_Id',
            'Kota_Atau_Kabupaten',
            'Ongkir',
            'Minimum_Kg',
            'Estimasi'
        ];
    }

    public function query()
    {
        return ShippingFee::query()
                ->select(
                    'provinces.prov_name',
                    'cities.city_id',
                    'cities.city_name',
                    'shipping_fees.fee',
                    'shipping_fees.minimum_kg',
                    'shipping_fees.shipping_estimation'
                )
                ->join(
                    'cities',
                    'shipping_fees.city_id',
                    'cities.city_id'
                )
                ->join(
                    'provinces',
                    'cities.prov_id',
                    'provinces.prov_id'
                );
    }
}
