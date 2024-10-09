<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Services\MidtransService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MidtransController extends Controller
{
    public function sendPayment($invoice)
    {
        try {
            DB::beginTransaction();

            $method = PaymentMethod::where('id', $invoice->payment_method_id)->firstOrFail();
            $data['invoice_number'] = $invoice->invoice_number;
            $data['invoice_amount'] = $invoice->invoice_amount;
            $data['billing_payment'] = $method->payment_name;
            $service = MidtransService::sendPayment($data);

            DB::commit();

            return $service;

        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage()
            ], 400);
        }
    }

    public function handlePayment(Request $request)
    {
        $this->validate($request, [
            'transaction_status'    => 'required',
            'settlement_time'       => 'required',
            'order_id'              => 'required'
        ]);

        try {
            DB::beginTransaction();

            $data = json_decode($request->getContent());
            MidtransService::handlePayment($data);

            DB::commit();

            return response()->json([
                'status' => 'success'
            ]);

        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage()
            ], 400);
        }
    }
}
