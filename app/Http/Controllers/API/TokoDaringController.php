<?php 

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\ProductSku;
use App\Models\PurchaseOrder;
use App\Services\TokoDaringService;
use Modules\Product\Entities\Product;
use Modules\Seller\Entities\SellerProductSKU;

class TokoDaringController extends Controller
{
    
    protected $clientId;
    protected $secretKey;
    protected $reportUrl;
    protected $confirmUrl;
    protected $sendToLKPP; // Y or T
    
    public function __construct()
    {
        $this->clientId = config('services.tokodaring.client_id');   
        $this->secretKey = config('services.tokodaring.secret_key');
        $this->reportUrl = config('services.tokodaring.report_url_dev');
        $this->confirmUrl = config('services.tokodaring.confirm_url_dev');
        $this->sendToLKPP = config('services.tokodaring.send_to_lkpp');
    }
    
    public function SSOLogin(Request $request)
    {
        if($request->header('X-Client-Id') == $this->clientId && $request->header('X-Client-Secret') == $this->secretKey){
            Log::info($request->getContent());
            $category = $request->header('X-Vertical-Type');
            $data = json_decode($request->getContent());
            $tokenLpse = $data->token ?? abort(404);
            $token = Str::random(100);
            $cekLpse = User::where('username_lpse', $data->payload->userName);
            if($cekLpse->count() > 0){
                $user = $cekLpse->first();
                $json = [
                    'code'  => 200,
                    'data'  => [
                        'token' =>  $token
                    ],
                    'message'   => null,
                    'status'    => true
                ];
                DB::table('lpse_account')->where('username', $data->payload->userName)->update([
                    'token'         => $token,
                    'token_lpse'    => $tokenLpse,
                ]);
               return response()->json($json, 200);
            }else{
                $token = Str::random(100);
                $dataLpse = array(
                    'username'  => $data->payload->userName,
                    'role'      => $data->payload->role,
                    'id_instansi' => $data->payload->idInstansi,
                    'nama_instansi' => $data->payload->namaInstansi,
                    'id_satker' => $data->payload->idSatker,
                    'nama_satker'=> $data->payload->namaSatker,
                    'token' => $token,
                    'token_lpse' => $tokenLpse
                );
                DB::table('lpse_account')->insert($dataLpse);
                $dataUser = [
                    'name'          => $data->payload->realName,
                    'email'         => $data->payload->email,
                    'password'      => Hash::make($tokenLpse),
                    'phone_number'  => $data->payload->phone,
                    'username_lpse' => $data->payload->userName,
                    'is_actived'    => 'Y',
                    'is_verified'   => 'Y'
                ];
                $user = User::create($dataUser);
                $user->assignRole("Customer");
                $json = [
                    'code'  => 200,
                    'data'  => [
                        'token' =>  $token
                    ],
                    'message'   => null,
                    'status'    => true
                ];
                return response()->json($json, 200);
            }
        }else{
            return response()->json([
                'code'  => 200,
                'data'  => null,
                'message'   => 'Gagal login, credential tidak match',
                'status'    => false
            ],200);
        }
    }
    
    public function reportTransaction($data=[])
    {
        $report = [
            'order_id'  => $data['order_number'],
            'pembeli'   => [
                'email' => $data['email'],
                'phone' => $data['phone'],
                'username' => $data['username']
            ],
            'items'     => $data['items'],
            'alamat_pengiriman' => $data['shipping_address'],
            'ongkos_kirim'      => $data['shipping_amount'],
            'ongkos_kirim_ppn'  => $data['shipping_amount_vat'],
            'ongkos_kirim_ppn_persentase' => $data['shipping_amount_vat_percent'],
            'ongkos_kirim_pph' => $data['shipping_income_tax'],
            'ongkos_kirim_pph_persentase' => $data['shipping_income_tax_percent'],
            'ppn_total' => $data['vat_total'],
            'pph_total' => $data['income_tax_total'],
            'valuasi' => $data['grand_total'],
            'metode_bayar' => $data['payment_method'],
            'nama_kurir' => $data['courier'],
            'token' => $data['token'] 
        ];
        if($this->sendToLKPP == "Y"){
            $client = new Client();
            $request = $client->request('POST',$this->reportUrl,[
                'json'      => $report,
                'headers'   => [
                    'X-Client-Id'       => $this->clientId,
                    'X-Client-Secret'   => $this->secretKey
                ]
            ]);
            DB::table('purchase_order_tokens')->insert([
                'order_number'  => $data['order_number'],
                'token'         => $data['token']
            ]);
            $response = $request->getBody();
            $resp = json_decode($response);
            $newToken = $resp->new_token;
            DB::table('lpse_account')->where('username',$data['username'])->update([
                'token_lpse' => $newToken
            ]);
            Log::info("Response Report #".$data['order_number']." : ".$response." ");
            return $resp->status;
        }
        Log::info(json_encode($report));
        return false;
    }

    public function confirmTransaction($data=[])
    {
        $confirm = [
            'order_id'          => $data['order_number'],
            "konfirmasi_ppmse"  => $data['status'],
            "keterangan_ppmse"  => $data['keterangan'],
            'metode_bayar'      => $data['metode_bayar'],
            'valuasi'           => $data['valuasi'],
            'token'             => $data['token']
        ];
        if($this->sendToLKPP == "Y") {
            $client = new Client();
            $request = $client->request('POST', $this->confirmUrl,[
                'json'  => $confirm,
                'headers'   => [
                    'X-Client-Id'       => $this->clientId,
                    'X-Client-Secret'   => $this->secretKey
                ]
            ]);
            $response = $request->getBody();
            $resp = json_decode($response);
            Log::info("Response Confirmation #".$data['order_number']." : ".$response." ");
            return $resp->status;
        }
        Log::info($confirm);
        return false;
    }

    public function getProducts(Request $request)
    {
        if($request->header('X-Client-Id') == $this->clientId && $request->header('X-Client-Secret') == $this->secretKey){
            $productSkus = new ProductSku();
            if($request->per_page > 0 && $request->per_page < 1000){
                $productSkus = $productSkus->limit($request->per_page)->offset(($request->page - 1) * $request->per_page);
            }else {
                $productSkus->limit(1000)->offset(($request->page - 1) * 1000);
            }
            if($request->per_page * $request->page >= $productSkus->count()){
                $lastPage = true;
            }else {
                $lastPage = false;
            }
            $productDatas = [];
            foreach($productSkus->get() as $sku){
                $formatProduct = TokoDaringService::formatProducts($sku);
                array_push($productDatas, $formatProduct);
            }
            return response()->json([
                'status'    => true,
                'code'      => 200,
                'data'      => $productDatas,
                'per_page'  => $request->per_page,
                'page'      => $request->page,
                'total'     => $productSkus->count(),
                'last_page' => $lastPage
            ]);
        }else {
            return response()->json([
                'code'  => 200,
                'data'  => null,
                'message'   => 'Gagal login, credential tidak match',
                'status'    => false
            ],200);
        }
    }
    
    public function testFormatReporting()
    {
        $order = PurchaseOrder::where('order_number','97221229113405')->firstOrFail();
        $lpseAccount = DB::table('users')
        ->select('lpse_account.username','lpse_account.token_lpse')
        ->join('lpse_account','users.username_lpse','=','lpse_account.username')
        ->where('users.username_lpse','=',"johndoe_999")->first();
        $report = TokoDaringService::formatReportingTransaction($order, $lpseAccount);
        return response()->json($report);
    }

    public function testFormatConfirmation()
    {
        $statusPpmse = false;
        $keteranganPpmse = "Order telah di cancel oleh customer";
        $order = PurchaseOrder::where('order_number','97221229113405')->firstOrFail();
        $formatConfirmation = TokoDaringService::formatConfirmTransaction($statusPpmse, $order, $keteranganPpmse);
        return response()->json($formatConfirmation);
    }
}
