<?php

namespace App\Imports;

use App\Models\ShippingFee;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ShippingFeeImport implements WithHeadingRow, ToArray
{
    public $shippingMethodId;

    public function __construct($shippingMethodId)
    {
        $this->shippingMethodId = $shippingMethodId;
    }

    public function array(array $array)
    {
        $array = array_map(function ($item) {
            return [
                'shipping_method_id'    => $this->shippingMethodId,
                "city_id"               => Arr::get($item, 'kota_id'),
                "fee"                   => Arr::get($item, 'ongkir'),
                "minimum_kg"            => Arr::get($item, 'minimum_kg'),
                "shipping_estimation"   => Arr::get($item, 'estimasi'),
            ];
        }, $array);

        foreach($array as $index => $arr) {
            ShippingFee::updateOrCreate(
                [
                    'shipping_method_id'    => $this->shippingMethodId,
                    'city_id'               => $arr['city_id']
                ],
                $array[$index]
            );
        }

    }
}
