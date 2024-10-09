<?php 

namespace App\Traits;

use App\Models\DeliveryOrder;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\Quotation;
use Carbon\Carbon;

trait GenerateNumber {

    private function generate($code, $id)
    {
        $date = \Carbon\Carbon::now()->format('Ymd');
        return $code.$date.str_pad($id, 6, '0', STR_PAD_LEFT);
    }

    public function generateRFQNumber()
    {
        $query = Quotation::where('number','LIKE','%RFQ%')->whereDate('date', Carbon::today());
        $temp = 1;
        if($query->count() > 0) {
            $temp = $query->count() + 1;
        }
        return $this->generate('RFQ', $temp);
    }

    public function generateQuotationNumber()
    {
        $query = Quotation::where('number','LIKE','%QN%')->whereDate('date', Carbon::today());
        $temp = 1;
        if($query->count() > 0) {
            $temp = $query->count() + 1;
        }
        return $this->generate('QN', $temp);
    }

    public function generatePurchaseOrderNumber()
    {
        $query = PurchaseOrder::where('order_number','LIKE','%PO%')->whereDate('purchase_date', Carbon::today());
        $temp = 1;
        if($query->count() > 0) {
            $temp = $query->count() + 1;
        }
        return $this->generate('PO', $temp);
    }

    public function generateDONumber()
    {
        $query = DeliveryOrder::whereDate('created_at', Carbon::today());
        $temp = 1;
        if($query->count() > 0) {
            $temp = $query->count() + 1;
        }
        return $this->generate('DO', $temp);
    }

    public function generateInvoiceNumber()
    {
        $query = Invoice::whereDate('created_at', Carbon::today());
        $temp = 1;
        if($query->count() > 0) {
            $temp = $query->count() + 1;
        }
        return $this->generate('INV', $temp);
    }

}