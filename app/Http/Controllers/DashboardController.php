<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\ChMessage;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\PurchaseOrder;
use App\Models\Quotation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $month = [];
    public $year;

    public function index(Request $request)
    {
        $rfq = Quotation::query()
                ->where(function($query) {
                    if(Auth::user()->hasRole('Customer')){
                        $query->where("user_id", Auth::user()->id);
                    }
                })
                ->where('number', 'LIKE', '%RFQ%');

        $quotation = Quotation::query()
                    ->where(function($query) {
                        if(Auth::user()->hasRole('Customer')){
                            $query->where("user_id", Auth::user()->id);
                        }
                    })
                    ->where('number', 'LIKE', '%QN%');

        $purchaseOrder = PurchaseOrder::query()
                        ->where(function($query) {
                            if(Auth::user()->hasRole('Customer')){
                                $query->where("user_id", Auth::user()->id);
                            }
                        });

        $invoice = Invoice::query()->whereHas('order', function($q) {
                        $q->where('user_id', Auth::user()->id);
                    });

        $customer = User::query()->whereHas('roles', function($q) {
            $q->where('name', 'Customer');
        });

        $data['orders'] = [
            'grandTotal'    => $purchaseOrder->sum('grand_total'),
            'count'         => $purchaseOrder->count()
        ];

        $data['invoice'] = [
            'grandTotal'    => $invoice->sum('invoice_amount'),
            'count'         => $invoice->count()
        ];

        $data['rfq'] = [
            'grandTotal'    => $rfq->sum('grand_total'),
            'count'         => $rfq->count()
        ];

        $data['quotation'] = [
            'grandTotal'    => $quotation->sum('grand_total'),
            'count'         => $quotation->count()
        ];

        $data['product'] = [
            'count' => Product::query()->count()
        ];

        $data['customer'] = [
            'count' => $customer->count()
        ];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::today()->startOfMonth()->subMonth($i)->format('M');
            $year = Carbon::today()->startOfMonth()->subMonth($i)->format('Y');
            array_push($this->month, $month. " ". $year);
        }

        if($request->ajax()) {

            $widgetRfqArr = ['rfq' => $this->widgetQuotation('rfq')];
            $widgetQuotationArr = ['quotation' => $this->widgetQuotation('quotation')];
            $widgetOrder = ['order' => $this->widgetPurchaseOrder()];
            $widgetInvoice = ['invoice' => $this->widgetInvoice()];
            $widgetProductStock = ['productStock' => $this->widgetProductStock()];

            $mergeWidget =  array_merge(
                                $widgetRfqArr, 
                                $widgetQuotationArr,
                                $widgetOrder,
                                $widgetInvoice,
                                $widgetProductStock,
                                ['period' => $this->month]
                            );

            return response()->json($mergeWidget);
        }

        return view('dashboard.index', $data);
    }

    private function makeWidget($query, $type, $item = [])
    {
        $widgetData = [];
        $itemUnique = [];
        
        $itemUnique = count($item) > 0 ? $item : [];

        if(count($itemUnique) == 0){
            foreach(Helpers::transactionStatus($type) as $stat) {
                array_push($itemUnique, $stat);
            }
        }

        foreach($itemUnique as $v => $key){
            array_push($widgetData, ["item" => $key, "data" => [], "color" => Helpers::randomColor() ]);
        }

         for($x = 0; $x < count($widgetData); $x++){
            foreach($this->month as $i){
                array_push($widgetData[$x]['data'], 0);
            }
        }
        
        foreach($query as $rowItem){
            $bulan = date('M Y', strtotime($rowItem->date));
            for($x = 0; $x < count($widgetData); $x++){
                if($rowItem->status == $widgetData[$x]['item']){
                    for($i = 0; $i < count($this->month); $i++){
                        if($this->month[$i] == $bulan){
                            $widgetData[$x]['data'][$i] = $rowItem->total;
                        }
                    }
                }
            }
        }

        return $widgetData;
    }

    private function widgetQuotation($type)
    {
        $status = Helpers::transactionStatus($type);

        $transLike = "QN";
        if($type == "rfq") $transLike = "RFQ"; 

        $query = Quotation::query()
                ->where('number', 'LIKE', '%'.$transLike.'%')
                ->join(
                    'status',
                    'quotations.status',
                    'status.id'
                )
                ->whereHas('quoteStatus', function($q) use($status) {
                    $q->whereIn('name', $status);
                })
                ->selectRaw('
                    status.name AS status, 
                    SUM(grand_total) AS total, 
                    date
                ')
                ->groupByRaw('
                    status, 
                    DATE_FORMAT(date, "%M")
                ')
                ->when(Auth::user()->hasRole('Customer'), function($q) {
                    $q->where('user_id', Auth::user()->id);
                })
                ->orderByRaw('DATE_FORMAT(date, "%M") DESC')
                ->get();
        
        return $this->makeWidget($query, $type);
    }

    private function widgetPurchaseOrder()
    {
        $status = Helpers::transactionStatus('purchase_order');

        $order = PurchaseOrder::query()
                    ->whereHas('status', function($q) use($status) {
                        $q->whereIn('name', $status);
                    })
                    ->join(
                        'status',
                        'purchase_orders.order_status',
                        'status.id'
                    )
                    ->selectRaw('
                        status.name AS status, 
                        SUM(grand_total) AS total, 
                        purchase_date AS date
                    ')
                    ->groupByRaw('
                        order_status, 
                        DATE_FORMAT(purchase_date, "%M")
                    ')
                    ->when(Auth::user()->hasRole('Customer'), function($q) {
                        $q->where('user_id', Auth::user()->id);
                    })
                    ->orderByRaw('DATE_FORMAT(purchase_date, "%M") DESC')
                    ->get();
        
        return $this->makeWidget($order, 'purchase_order');
    }

    private function widgetInvoice()
    {
        $status = Helpers::transactionStatus('invoice');

        $invoice = Invoice::query()
                    ->whereIn('status', $status)
                    ->selectRaw('
                        status, 
                        SUM(invoice_amount) AS total, 
                        invoice_date AS date
                    ')
                    ->groupByRaw('
                        status, 
                        DATE_FORMAT(invoice_date, "%M")
                    ')
                    ->when(Auth::user()->hasRole('Customer'), function($q) {
                        $q->whereHas('order', function($query) {
                            $query->where('user_id', Auth::user()->id);
                        });
                    })
                    ->orderByRaw('DATE_FORMAT(invoice_date, "%M") DESC')
                    ->get();
        
        return $this->makeWidget($invoice, 'invoice');
    }

    private function widgetProductStock()
    {
        $status = ["Stok Tersedia", "Stok Habis"];

        $product =  DB::query()->fromSub(function($query) {
                        $query->select(
                            DB::raw("SUM(product_skus.product_stock) AS stock"),
                            DB::raw('CASE 
                                        WHEN SUM(product_skus.product_stock) > 0 
                                            THEN "Stok Tersedia" 
                                        ELSE "Stok Habis"
                                    END AS 
                                    STATUS')
                        )
                        ->from("products")
                        ->join(
                            "product_skus",
                            "products.id",
                            "product_skus.product_id"
                        )
                        ->groupBy('products.id');
                    }, 'a')
                    ->select(
                        'a.status',
                        DB::raw('COUNT(*) AS total')
                    )
                    ->groupBy('a.status')
                    ->get();
        
        $widgetArr = Collection::make([]);

        foreach($status as $stat) {

            $total = 0;

            foreach($product as $prod){
                if($prod->status == $stat) {
                    $total = $prod->total;
                }
            }

            $widgetArr->push([
                'name'      => $stat,
                'total'     => $total
            ]);
        }

        return $widgetArr->toArray();
    }

    public function notifications(Request $request)
    {
       
        $allNotifications = Auth::user()->notifications()->paginate(5);
        $countUnread = Auth::user()->unreadNotifications()->count();
        if($request->get('opsi') == "markAsRead") {
            Auth::user()->unreadNotifications()->get()->map(function($n) {
                $n->markAsRead();
            });
        } 
        return response()->json([
            'data'      => $allNotifications,
            'notif'     => $countUnread
        ]);
    }

    public function messages()
    {
        $message = ChMessage::where('to_id', Auth::user()->id)
                    ->join(
                        'users',
                        'users.id',
                        'ch_messages.from_id'
                    )
                    ->leftJoin(
                        'merchants',
                        'merchants.user_pic',
                        'users.id'
                    )
                    ->selectRaw('
                        users.id,
                        CASE WHEN merchants.name IS NOT NULL 
                            THEN merchants.name
                            ELSE users.name
                        END AS name,
                        users.email,
                        users.photo,
                        ch_messages.from_id,
                        ch_messages.seen,
                        ch_messages.body,
                        ch_messages.created_at
                    ')
                    ->orderByDesc('created_at')
                    ->paginate(10);

        return response()->json([
            'data'      => $message,
            'message'   => ChMessage::where('to_id', Auth::user()->id)->where('seen', 0)->count()
        ]);
    }
}
