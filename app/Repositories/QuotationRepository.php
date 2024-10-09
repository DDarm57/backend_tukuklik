<?php 

namespace App\Repositories;

use App\Models\AddressRequest;
use App\Models\CustomerAddresses;
use App\Models\MerchantAddress;
use App\Models\Quotation;

class QuotationRepository {

    public static function quotation($filter, $id = NULL)
    {
        $query = Quotation::query()
        ->with(['user','merchant','quoteStatus'])
        ->where('grand_total', '>', '0')
        ->when($filter['type'], function($query) use($filter) {

            $query = $query->join('transaction_channels', function($join) use($query, $filter) {
                
                $join->on('transaction_channels.id','=','quotations.channel_id');
                
                if($filter['type'] == 'RFQ') {
                    $join->on('transaction_channels.rfq_number', '=', 'quotations.number');
                }else {
                    $join->on('transaction_channels.quotation_number', '=', 'quotations.number');
                }
                
            });

        })
        ->where(function($q) use($filter) {
            
            $fromDate = $filter['fromDate'] ?? '';
            $toDate = $filter['toDate'] ??  '';

            if($fromDate != '') {
                $q->whereDate('date', '>=', $filter['fromDate']);
            }

            if($toDate != ''){
                $q->whereDate('date', '<=', $filter['toDate']);
            }

        })
        ->when($filter['status'] ?? null, function($query) use($filter) {
            $query->whereHas('quoteStatus', function($q) use($filter) {
                $q->where('name', $filter['status']);
            });
        })
        ->when($id, function($query) use ($id) {
            $query->where('user_id', $id);
        })
        ->latest()
        ->get();
        return $query;
    }

    public static function shippingAddress($number)
    {
        $query = AddressRequest::where('number', $number)
                ->leftJoin('provinces','address_requests.shipping_province_id', '=', 'provinces.prov_id')
                ->leftJoin('cities', 'address_requests.shipping_city_id','=', 'cities.city_id')
                ->leftJoin('districts', 'address_requests.shipping_district_id','=','districts.dis_id')
                ->leftJoin('subdistricts', 'address_requests.shipping_subdistrict_id','=','subdistricts.subdis_id');
        return $query;
    }

    public static function billingAddress($number)
    {
        $query = AddressRequest::where('number', $number)
                ->leftJoin('provinces','address_requests.billing_province_id', '=', 'provinces.prov_id')
                ->leftJoin('cities', 'address_requests.billing_city_id','=', 'cities.city_id')
                ->leftJoin('districts', 'address_requests.billing_district_id','=','districts.dis_id')
                ->leftJoin('subdistricts', 'address_requests.billing_subdistrict_id','=','subdistricts.subdis_id');
        return $query;
    }

}