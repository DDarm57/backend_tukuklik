<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\Quotation;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use PDO;

class ExpiredAndReminderTrans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trans:exp_and_reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder And Execute Expired Transaction';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /* Expired */
        $expiredQuote = Quotation::whereIn('status', [1,2])
                        ->where('deadline_date', '<' ,Carbon::now());
        foreach($expiredQuote->get() as $expQuote) {
            NotificationService::quotation($expQuote->number, 'expired');
        }
        $expiredQuote->update(['status' => 12]);

        $expiredPO = PurchaseOrder::where('order_status', 1)
                    ->where('purchase_deadline_date', '<=', Carbon::today()->toDateString());
        foreach($expiredPO->get() as $expPO) {
            NotificationService::purchaseOrder($expPO->order_number, 'expired');
        }
        $expiredPO->update(['order_status' => 12]);

        $expiredInvoice = Invoice::where('status', 'Belum Dibayar')
                            ->where('due_date', '<=', Carbon::now());
        foreach($expiredInvoice->get() as $expInv) {
            NotificationService::invoice($expInv, 'due_date');
        }
        $expiredInvoice->update(['status' => 'Jatuh Tempo']);

        /* Reminder */
        $quotation = Quotation::whereIn('status', ['1','2'])->get();
        foreach($quotation as $quote) {
            NotificationService::quotation($quote->number, 'reminder');
        }

        $purchaseOrder = PurchaseOrder::where('order_status', 1)->get();
        foreach($purchaseOrder as $po){
            NotificationService::purchaseOrder($po->order_number, 'reminder');
        }

        $invoice = Invoice::where('status', 'Belum Dibayar')
                    ->whereDate('due_date', Carbon::tomorrow()->toDateString())
                    ->get();
        foreach($invoice as $inv) {
            NotificationService::invoice($inv, 'reminder');
        }
        
    }
}
