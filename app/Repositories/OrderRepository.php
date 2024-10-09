<?php 

namespace App\Repositories;

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;

class OrderRepository {

    public static function findAll($filter)
    {
        return PurchaseOrder::query()
            ->with([
                'quotation', 
                'status', 
                'quotation.user', 
                'quotation.merchant'
            ])
            ->where(function($q) use($filter) {
            
                $fromDate = $filter['fromDate'] ?? '';
                $toDate = $filter['toDate'] ?? '';
                
                if($fromDate != '') {
                    $q->whereDate('purchase_date', '>=', $filter['fromDate']);
                }
    
                if($toDate != ''){
                    $q->whereDate('purchase_date', '<=', $filter['toDate']);
                }
    
            })
            ->when($filter['status'] ?? null, function($query) use($filter) {
                $query->whereHas('status', function($q) use($filter) {
                    $q->where('name', $filter['status']);
                });
            })
            ->where(function($query) {
                if(Auth::user()->hasRole('Customer')){
                    $query->where("user_id", Auth::user()->id);
                }
            })
        ->latest()
        ->get();
    }

}