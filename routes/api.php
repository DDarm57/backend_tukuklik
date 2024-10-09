<?php

use App\Http\Controllers\API\Auth\ForgotPasswordController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\GeneralSettingController;
use App\Http\Controllers\API\HomepageController;
use App\Http\Controllers\API\MerchantController;
use App\Http\Controllers\API\PageController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\TokoDaringController;
use App\Http\Controllers\API\Transaction\DeliveryOrderController;
use App\Http\Controllers\API\Transaction\InvoiceController;
use App\Http\Controllers\API\Transaction\PurchaseOrderController;
use App\Http\Controllers\API\Transaction\QuotationController;
use App\Http\Controllers\API\WishlistController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\Payment\MidtransController;
use App\Http\Middleware\ConfirmAPIHeader;
use App\Http\Middleware\MakeSureAPIKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('tokodaring')->group(function() {
    Route::post('/sso_login', [TokoDaringController::class, 'SSOLogin']);
    Route::post('/products', [TokoDaringController::class, 'getProducts']);
    Route::get('/format_reporting', [TokoDaringController::class, 'testFormatReporting']);
});

Route::prefix('v1')->group(function() {
    Route::middleware([ConfirmAPIHeader::class, MakeSureAPIKey::class])->group(function() {
        Route::prefix('auth')->group(function() {
            Route::post('login', [LoginController::class, 'authenticate']);
            Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
            Route::post('register', [RegisterController::class, 'register']);
            Route::post('request-reset-password', [ForgotPasswordController::class, 'resetPasswordLink']);
            Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword']);
        });
        Route::prefix('homepage')->group(function() {
            Route::get('banner', [HomepageController::class, 'banner']);
            Route::get('categories', [HomepageController::class, 'categories']);
            Route::get('flash-sale', [HomepageController::class, 'flashSaleProduct']);
            Route::get('product-by-category', [HomepageController::class, 'productPerCategory']);
            Route::get('products', [HomepageController::class, 'productAll']);
        });
        Route::prefix('merchant')->group(function() {
            Route::get('/', [MerchantController::class, 'showMerchant']);
            Route::get('/{id}', [MerchantController::class, 'getMerchantById']);
        });
        Route::prefix('page')->group(function() {
            Route::post('contact', [PageController::class, 'sendContactMessage']);
            Route::post('showPage', [PageController::class, 'showPage']);
        });
        Route::prefix('general_settings')->group(function() {
            Route::get('/', [GeneralSettingController::class, 'index']);
        });
        Route::prefix('product')->group(function() {
            Route::get('/', [ProductController::class, 'getAllProduct']);
            Route::get('/show/{slug}', [ProductController::class, 'showProduct']);
            Route::get('/references', [ProductController::class, 'productReferences']);
            Route::get('/last-visit', [ProductController::class, 'productLastVisit']);
            Route::post('/sku-by-varian', [ProductController::class, 'getSkuByVariant']);
        });
        Route::get('general-setting', [GeneralSettingController::class, 'showGeneralSetting']);
        Route::middleware(['auth:sanctum'])->group(function() {
            Route::prefix('profile')->group(function() {
                Route::get('/', [ProfileController::class, 'myProfile']);
                Route::get('notification', [ProfileController::class, 'notifications']);
                Route::post('update', [ProfileController::class, 'updateProfile']);
            });
            Route::prefix('cart')->group(function() {
                Route::get('/', [CartController::class, 'showCart']);
                Route::post('add', [CartController::class, 'addCart']);
                Route::post('delete/{id}', [CartController::class, 'deleteCart']);
                Route::post('adjust-qty/{id}', [CartController::class, 'adjustQty']);
            });
            Route::prefix('wishlist')->group(function() {
                Route::get('/', [WishlistController::class, 'index']);
                Route::delete('/{id}', [WishlistController::class, 'deleteWishlist']);
                Route::post('add', [WishlistController::class, 'addWishlist']);
                Route::post('add-to-cart', [WishlistController::class, 'addWishlistToCart']);
            });
            Route::group(['middleware' => ['permission:transaction']], function() {
                Route::prefix('rfq')->group(function() {
                    Route::delete('/{number}', [QuotationController::class, 'deleteRFQ']);
                    Route::get('/', [QuotationController::class, 'rfqIndex']);
                    Route::post('/', [QuotationController::class, 'submitRFQ']);
                    Route::post('/{number}', [QuotationController::class, 'submitQuotation'])->name('rfq.update');
                    Route::post('/{number}/reject', [QuotationController::class, 'rejectRFQ'])->name('rfq.update');
                });
                Route::prefix('quotation')->group(function() {
                    Route::get('/', [QuotationController::class, 'quotationIndex']);
                    Route::get('/{number}', [QuotationController::class, 'detailQuote']);
                    Route::post('/{number}/reject', [QuotationController::class, 'rejectQuotation']);
                    Route::post('/{number}/negotiation', [QuotationController::class, 'submitNegotiation']);
                    Route::post('/{number}/negotiation-message', [QuotationController::class, 'sendNegotiateMessage']);
                    Route::post('/{number}/reject-negotiation', [QuotationController::class, 'rejectNegotiation']);
                });
                Route::prefix('purchase-order')->group(function() {
                    Route::get('/', [PurchaseOrderController::class, 'index'])->name('purchase-order.index');
                    Route::post('/', [PurchaseOrderController::class, 'createPurchaseOrder'])->name('purchase-order.create');
                    Route::post('/confirm', [PurchaseOrderController::class, 'confirmPurchaseOrder'])->name('purchase-order.create');
                    Route::post('/reject', [PurchaseOrderController::class, 'rejectPurchaseOrder'])->name('purchase-order.index');
                    Route::get('/{number}', [PurchaseOrderController::class, 'showPurchaseOrder'])->name('purchase-order.index');
                    Route::delete('/{number}', [PurchaseOrderController::class, 'deletePurchaseOrder'])->name('purchase-order.delete');
                });
                Route::post('purchase-order-documents', [PurchaseOrderController::class, 'uploadDocument']);
                Route::delete('purchase-order-documents/{id}', [PurchaseOrderController::class, 'deleteDocument']);
                Route::get('purchase-order-documents/{number}', [PurchaseOrderController::class, 'documentByPO']);
                Route::prefix('delivery-order')->group(function() {
                    Route::post('/', [DeliveryOrderController::class, 'createDeliveryOrder'])->name('shipping-order.create');
                    Route::get('/', [DeliveryOrderController::class, 'index'])->name('shipping-order.index');
                    Route::get('/{number}', [DeliveryOrderController::class, 'showDeliveryOrder'])->name('shipping-order.index');
                    Route::post('/confirm', [DeliveryOrderController::class, 'confirmDeliveryOrder'])->name('shipping-order.create');
                });
                Route::prefix('invoice')->group(function() {
                    Route::get('/', [InvoiceController::class, 'index'])->name('invoice.view');
                    Route::post('/payment', [InvoiceController::class, 'createPayment']);
                    Route::get('/{number}', [InvoiceController::class, 'showInvoice'])->name('invoice.view');
                    Route::post('/submit-payment', [InvoiceController::class, 'uploadPayment'])->name('invoice.view');
                    Route::post('/confirm-payment', [InvoiceController::class, 'confirmPayment'])->name('invoice.view');
                });
            });
            Route::prefix('area')->group(function() {
                Route::get('province', [AreaController::class, 'province']);
                Route::get('city/{province_id}', [AreaController::class, 'getCitiesByProvince']);
                Route::get('district/{city_id}', [AreaController::class, 'getDistrictsByCity']);
                Route::get('subdistrict/{district_id}', [AreaController::class, 'getSubdistrictsByDistrict']);
            });
            Route::prefix('address')->group(function() {
                Route::get('/', [CustomerController::class, 'listAddress']);
                Route::get('/{id}', [CustomerController::class, 'addressById']);
                Route::delete('/{id}', [CustomerController::class, 'deleteAddress']);
                Route::put('/{id}', [CustomerController::class, 'updateAddress']);
                Route::post('/', [CustomerController::class, 'saveAddress']);
            });
        });
    });
    Route::post('midtrans', [MidtransController::class, 'handlePayment']);
});