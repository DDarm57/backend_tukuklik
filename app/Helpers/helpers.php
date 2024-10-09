<?php 

namespace App\Helpers;

use App\Models\AddressRequest;
use App\Models\Cart;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\CustomerAddresses;
use App\Models\PaymentMethod;
use App\Models\ProductRequest;
use App\Models\ProductSku;
use App\Models\ShippingFee;
use App\Models\ShippingMethod;
use App\Models\Tax;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Helpers {

    public static function roleForCustomer()
    {
        return 'Customer';
    }

    public static function requestExcept($request, $additional=[])
    {
        $array = ['_method', '_token', 'password_confirmation', 'role'];
        $merge = array_merge($array, $additional);
        return $request->except($merge);
    }

    public static function isModuleShow($name, $url)
    {
        $stat = false;
        foreach(self::dashboardMenu() as $d) {
            foreach($d['menu'] as $m) {
                if($m['link'] == $url) {
                    $explode = explode('.', $m['permission']);
                    $name == $explode[0] ? $stat = true : null;
                }
            }
        }
        return $stat;
    }

    public static function dashboardMenu()
    {
        return [
            [
                'name'          => 'Dashboard',
                'is_parent'     => 'T',
                'link'          => url('dashboard'),
                'icon'          => 'bi bi-grid',
                'permission'    => 'dashboard.dashboard_view',
                'menu'          => [],
            ],
            [
                'name'          => 'Pengguna',
                'is_parent'     => 'Y',
                'link'          => '',
                'icon'          => 'bi bi-people',
                'permission'    => 'user',
                'menu'          => [
                    [
                        'name'          => 'Staff',
                        'permission'    => 'user.staff_view',
                        'link'          => url('dashboard/staff'),
                    ],
                    [
                        'name'          => 'Customer',
                        'permission'    => 'user.customer_view',
                        'link'          => url('dashboard/customer'),
                    ],
                    [
                        'name'          => 'Organisasi',
                        'permission'    => 'user.organization_view',
                        'link'          => url('dashboard/organization'),
                    ]
                ],
            ],
            [
                'name'          => 'Peran',
                'is_parent'     => 'Y',
                'link'          => '',
                'icon'          => 'bi bi-person-dash',
                'permission'    => 'role',
                'menu'          => [
                    [
                        'name'          => 'Peran',
                        'permission'    => 'role.role_view',
                        'link'          => url('dashboard/role'),
                    ],
                    [
                        'name'          => 'Hak Akses',
                        'permission'    => 'role.permission_view',
                        'link'          => url('dashboard/permission'),
                    ],
                ],
            ],
            [
                'name'          => 'Produk',
                'is_parent'     => 'Y',
                'link'          => '',
                'icon'          => 'bi bi-basket3',
                'permission'    => 'product',
                'menu'          => [
                    [
                        'name'          => 'Kategori',
                        'permission'    => 'product.category_view',
                        'link'          => url('dashboard/category'),
                    ],
                    [
                        'name'          => 'List Produk',
                        'permission'    => 'product.product_view',
                        'link'          => url('dashboard/product'),
                    ],
                    [
                        'name'          => 'Buat Produk Baru',
                        'permission'    => 'product.product_create',
                        'link'          => url('dashboard/product/create'),
                    ],
                    [
                        'name'          => 'Unit',
                        'permission'    => 'product.unit_view',
                        'link'          => url('dashboard/unit'),
                    ],
                    [
                        'name'          => 'Review Produk',
                        'permission'    => 'product.product-review_view',
                        'link'          => url('dashboard/product-review'),
                    ],
                    [
                        'name'          => 'Varian',
                        'permission'    => 'product.attribute_view',
                        'link'          => url('dashboard/attribute'),
                    ],
                ],
            ],
            [
                'name'          => 'Transaksi',
                'is_parent'     => 'Y',
                'link'          => '',
                'icon'          => 'bi bi-cart4',
                'permission'    => 'transaction',
                'menu'          => [
                    [
                        'name'          => 'Permintaan',
                        'permission'    => 'transaction.rfq_view',
                        'link'          => url('dashboard/request-for-quotation'),
                    ],
                    [
                        'name'          => 'Penawaran',
                        'permission'    => 'transaction.quotation_view',
                        'link'          => url('dashboard/quotation'),
                    ],
                    [
                        'name'          => 'Pesanan',
                        'permission'    => 'transaction.purchase-order_view',
                        'link'          => url('dashboard/purchase-order'),
                    ],
                ],
            ],
            [
                'name'          => 'Pengiriman',
                'is_parent'     => 'Y',
                'link'          => '',
                'icon'          => 'bi bi-truck',
                'permission'    => 'shipping',
                'menu'          => [
                    [
                        'name'          => 'Order Pengiriman',
                        'permission'    => 'shipping.shipping-order_view',
                        'link'          => url('dashboard/shipping-order'),
                    ],
                    [
                        'name'          => 'Metode Pengiriman',
                        'permission'    => 'shipping.shipping-method_view',
                        'link'          => url('dashboard/shipping-method'),
                    ],
                ],
            ],
            [
                'name'          => 'Marketing',
                'is_parent'     => 'Y',
                'link'          => '',
                'icon'          => 'bi bi-tv',
                'permission'    => 'marketing',
                'menu'          => [
                    [
                        'name'          => 'Program Promo',
                        'permission'    => 'marketing.flash-deals_view',
                        'link'          => url('dashboard/flash-deals'),
                    ],
                    [
                        'name'          => 'Kupon Diskon',
                        'permission'    => 'marketing.coupon_view',
                        'link'          => url('dashboard/coupon'),
                    ],
                    [
                        'name'          => 'Banner',
                        'permission'    => 'marketing.banner_view',
                        'link'          => url('dashboard/banner'),
                    ],
                ],
            ],
            [
                'name'          => 'Pembayaran',
                'is_parent'     => 'Y',
                'link'          => '',
                'icon'          => 'bi bi-bank',
                'permission'    => 'payment',
                'menu'          => [
                    [
                        'name'          => 'Metode Pembayaran',
                        'permission'    => 'payment.payment-method_view',
                        'link'          => url('dashboard/payment-method'),
                    ],
                    // [
                    //     'name'          => 'Riwayat Pembayaran',
                    //     'permission'    => 'payment.payment-history_view',
                    //     'link'          => url('dashboard/payment-history'),
                    // ],
                ],
            ],
            [
                'name'          => 'Penjual',
                'is_parent'     => 'Y',
                'link'          => '',
                'icon'          => 'bi bi-building',
                'permission'    => 'merchant',
                'menu'          => [
                    [
                        'name'          => 'List Penjual',
                        'permission'    => 'merchant.merchant_view',
                        'link'          => url('dashboard/merchant'),
                    ],
                ],
            ],
            [
                'name'          => 'Tagihan',
                'is_parent'     => 'T',
                'link'          => url('dashboard/invoice'),
                'icon'          => 'bi bi-archive-fill',
                'permission'    => 'invoice.invoice_view',
                'menu'          => [],
            ],
            [
                'name'          => 'Log Audit',
                'is_parent'     => 'T',
                'link'          => url('dashboard/audit'),
                'icon'          => 'bi bi-eye',
                'permission'    => 'audit.audit_view',
                'menu'          => [],
            ],
            [
                'name'          => 'Frontend Page',
                'is_parent'     => 'T',
                'link'          => url('dashboard/frontend'),
                'icon'          => 'bi bi-window-dock',
                'permission'    => 'frontend.frontend_view',
                'menu'          => [],
            ],
            [
                'name'          => 'Pengaturan',
                'is_parent'     => 'Y',
                'link'          => '',
                'icon'          => 'bi bi-grid-3x3-gap',
                'permission'    => 'setting',
                'menu'          => [
                    [
                        'name'          => 'Pengaturan Umum',
                        'permission'    => 'setting.general-setting_view',
                        'link'          => url('dashboard/general-setting'),
                    ],
                    // [
                    //     'name'          => 'Payment Gateway',
                    //     'permission'    => 'setting.payment-gateway_view',
                    //     'link'          => url('dashboard/payment-gateway'),
                    // ],
                    [
                        'name'          => 'VAT / TAX',
                        'permission'    => 'setting.vat_view',
                        'link'          => url('dashboard/vat'),
                    ],
                    [
                        'name'          => 'Homepage SEO',
                        'permission'    => 'setting.homepage-seo_view',
                        'link'          => url('dashboard/homepage'),
                    ],
                    [
                        'name'          => 'Informasi Perusahaan',
                        'permission'    => 'setting.company-information_view',
                        'link'          => url('dashboard/company'),
                    ],
                ],
            ],
        ];
    }

    /**
     * The helper for strict value must be array
     *
     * @param $value
     * @return array
     */
    public static function arrStrict($value): array
    {
        return is_array($value) ? $value : [$value];
    }

    public static function formatAddress($address)
    {
        if($address == null) {
            return 'Alamat belum ditambahkan';
        }

        $fullAddress = $address->full_address ?? $address->shipping_address;

        return 
        $fullAddress. 
        ", ". $address->subdis_name.
        ", ". $address->dis_name. 
        ", ". $address->city_name. 
        ", ". $address->prov_name. 
        ", Indonesia".
        ", ". $address->shipping_postcode;
    }

    public static function paymentType()
    {
        return ['Cash Before Delivery', 'Term Of Payment'];
    }

    public static function calculateTax($isPkp, $basePrice)
    {
        if($isPkp == 'Y') {
            $percentage = Tax::where('name', 'LIKE', '%PPN%')->first()->tax_percentage ?? 11;
            $calculate = $basePrice * ($percentage/100);
            return $calculate;
        }
        return 0;
    }

    public static function getTax($isPkp) 
    {
        if($isPkp == 'Y') {
            $percentage = Tax::where('name', 'LIKE', '%PPN%')->first()->tax_percentage ?? 11;
            return $percentage;
        }
        return 0;
    }

    public static function formatRupiah($amount, $curr = null)
    {
        return $curr != null ? "Rp. " : ''.number_format($amount,0, '.', '.');
    }

    public static function getMerchantAddress($id)
    {
        return self::formatAddress(UserRepository::merchantAddress($id)->first());
    }

    public static function frontendMenu()
    {

    }

    public static function getShippingFeeEstimation($transactionId = null)
    {
        $cityId = null;

        $addressRequest = AddressRequest::where('number', $transactionId)->first();
        $cityId = $addressRequest->shipping_city_id ?? null;
        
        if($cityId == null){
            $customerAddress = CustomerAddresses::where('user_id', Auth::user()->id);
            if($customerAddress->count() == 0){
                return 0;
            }
            $cityId = $customerAddress->first()->shipping_city_id;
        }
        
        $fee = ShippingFee::query()->where('city_id', $cityId)->first();
        
        $min = $fee->minimum_kg;
        $cart = Cart::where('user_id', Auth::user()->id);
        $lengthTotal = 0;
        $breadthTotal = 0;
        $heightTotal = 0;
        $weightTotal = 0;
        $product = ProductRequest::where('number', $transactionId)->get();

        if($cart->count() > 0){
            $product = $cart->get();
        }
        
        foreach($product as $prod) {
            $quantity = $prod->quantity ?? $prod->qty;
            $lengthTotal += $quantity * $prod->productSku->length;
            $breadthTotal += $quantity * $prod->productSku->breadth;
            $heightTotal += $quantity * $prod->productSku->height;
            $weightTotal += $quantity * ($prod->productSku->weight/1000);
        }

        // $shipping = ShippingMethod::query()->first();

        if($weightTotal <= $min) {
            $shippingFee = (($lengthTotal * $breadthTotal * $heightTotal) / 4000) * ($fee->fee ?? self::defaultCost());
        }else {
            $shippingFee = $fee->fee * $weightTotal;
        }

        return "Rp. ".number_format($shippingFee,0,'.','.') ;

    }

    private static function defaultCost()
    {
        return 10000;
    }

    public static function colorStatus($status)
    {
        $payload =  [
            "Menunggu Konfirmasi Penjual" => "badge bg-warning",
            "Menunggu Konfirmasi Pembeli" => "badge bg-warning",
            "Negosiasi"                   => "badge bg-info",
            "Quotation Dibuat"            => "badge bg-primary",
            "PO Dibuat"                   => "badge bg-primary",
            "Pesanan Diproses"            => "badge bg-info",
            "Dalam Pengiriman"            => "badge bg-info",
            "Terkirim"                    => "badge bg-success",
            "Selesai"                     => "badge bg-secondary",
            "Kadaluwarsa"                 => "badge bg-danger",
            "Ditolak Pembeli"             => "badge bg-danger",
            "Ditolak Penjual"             => "badge bg-danger",
            "Menunggu Konfirmasi Penjual" => "badge bg-primary",
            "Belum Dibayar"               => "badge bg-info",
            "Sudah Dibayar"               => "badge bg-secondary",
            "Jatuh Tempo"                 => "badge bg-danger",
            "Kadaluwarsa"                 => "badge bg-danger",
            "Menunggu Pembayaran"         => "badge bg-warning"
        ];
        return "<span class='".$payload[$status]."' >".$status."</span> "; 
    }

    public static function additionalMail()
    {
        $mail = Collection::make([]);
        $user = UserRepository::staffOnly();
        foreach($user as $staff) {
            $mail->push($staff->email);
        }
        return $mail;
    }

    public static function photoProfile($photo)
    {
        return  $photo != null 
                ? Storage::url($photo) 
                : url('images/default-profile.png');
    }

    public static function notifForAllStaffOrMerchant($userId = null)
    {
        return User::query()->whereHas('roles', function($q) use ($userId) {
            $q->whereNotIn('roles.name', ['Customer','Seller']);

            if($userId != null)  {
                $q->orWhere('id', $userId);
            }
            
        })->get();
    }

    public static function generalSetting()
    {
        return DB::table('general_settings')->first();
    }

    public static function transactionStatus($type)
    {
        switch($type)
        {
            case 'rfq' :
                return ['Menunggu Konfirmasi Penjual', 'Quotation Dibuat', 'Kadaluwarsa', 'Ditolak Penjual'];
            break;
            
            case 'quotation' : 
                return ['Menunggu Konfirmasi Pembeli', 'PO Dibuat', 'Kadaluwarsa', 'Negosiasi', 'Ditolak Pembeli', 'Ditolak Penjual'];
            break;

            case 'purchase_order' :
                return ['Menunggu Konfirmasi Penjual', 'Pesanan Diproses', 'Dalam Pengiriman', 'Selesai','Ditolak Penjual', 'Kadaluwarsa', 'Menunggu Pembayaran'];
            break;

            case 'invoice' :
                return ['Belum Dibayar', 'Sudah Dibayar', 'Jatuh Tempo', 'Kadaluwarsa'];
            break;
        }
    }

    private static function randomColorPart() {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }
    
    public static function randomColor() {
        return "#". self::randomColorPart() . self::randomColorPart() . self::randomColorPart();
    }

    public static function image($path){

        if(str_contains($path, '/storage/'))
        {
            $url = $path;
            $path = str_replace('/storage/', '', $path);
        }
        else 
        {
            $url = Storage::url($path);
        }

        if(Storage::disk('public')->exists($path) && $path != '') {
            return $url;
        }

        return url('images/no-image-icon.png');
    }

    public static function productTitle($str)
    {
        if(strlen($str) > 35)
        {
            return substr($str, 0, 35) . "...";
        }
        
        return $str;
    }

    public static function countCat($id)
    {
        return CategoryProduct::query()->where('category_id', $id)->count();
    }

    public static function sanctumExpired()
    {
        return 60 * 24 * 7; // 7days;
    }

    public static function pages()
    {
        return DB::table('pages')->latest()->get();
    }

    public static function payments()
    {
        return PaymentMethod::where('payment_service', 'Midtrans')->get();
    }

    public static function categories()
    {
        return Category::query()->where('depth_level', 1)->get();
    }

    private static function denominate($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = self::denominate($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = self::denominate($nilai/10)." puluh". self::denominate($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . self::denominate($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = self::denominate($nilai/100) . " ratus" . self::denominate($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . self::denominate($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = self::denominate($nilai/1000) . " ribu" . self::denominate($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = self::denominate($nilai/1000000) . " juta" . self::denominate($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = self::denominate($nilai/1000000000) . " milyar" . self::denominate(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = self::denominate($nilai/1000000000000) . " trilyun" . self::denominate(fmod($nilai,1000000000000));
		}     
		return $temp;
	}
 
	public static function countedAmount($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim(self::denominate($nilai));
		} else {
			$hasil = ucwords(trim(self::denominate($nilai))). " Rupiah";
		}     		
		return $hasil;
	}

}