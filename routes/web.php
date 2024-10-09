<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\HomepageController;
use App\Http\Controllers\Frontend\MerchantController as FrontendMerchantController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\SellerController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\GeneralSettingController;
use App\Http\Controllers\Marketing\BannerController;
use App\Http\Controllers\Marketing\CouponController;
use App\Http\Controllers\Marketing\FlashDealController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\Notification\FirebaseController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Product\AttributeController;
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Product\MediaController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductReviewController;
use App\Http\Controllers\Product\UnitTypeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\Transaction\DeliveryOrderController;
use App\Http\Controllers\Transaction\InvoiceController;
use App\Http\Controllers\Transaction\PurchaseOrderController;
use App\Http\Controllers\Transaction\QuotationController;
use App\Http\Controllers\VATController;
use App\Http\Middleware\ProductVisitor;
use App\Http\Middleware\SessionTokodaring;
use App\Http\Middleware\SystemVisitor;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Authentication */
Route::middleware([SystemVisitor::class])->group(function() {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('auth.login');
    Route::get('/register', [RegisterController::class, 'showRegisterForm']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'index']);
    Route::post('/reset-password-link', [ForgotPasswordController::class, 'resetPasswordLink'])->name('auth.reset-password-link');
    Route::get('/reset-password/{link}', [ForgotPasswordController::class, 'showResetPassword']);
    Route::post('/reset-password/{userId}', [ForgotPasswordController::class, 'resetPassword'])->name('auth.reset-password');
    Route::post('/register', [RegisterController::class, 'register'])->name('auth.register');
    Route::get('/verification/{token}', [RegisterController::class, 'verify'])->name('auth.verify');
    Route::get('logout', [LoginController::class, 'logout'])->name('auth.logout');
    /* Save Firebase Token */
    Route::post('firebase-token', [FirebaseController::class, 'store'])->name('firebase.token');

    /* Frontend */
    Route::get('/', [HomepageController::class, 'homePage']);
    Route::get('/product/{slug}', [FrontendProductController::class, 'showProduct'])->name('product-detail')->middleware(ProductVisitor::class);
    Route::get('/products/{slug?}/{type?}', [FrontendProductController::class, 'productByCategoryOrTag']);
    Route::get('/merchant/{id}', [FrontendMerchantController::class, 'showMerchant']);
    Route::post('/product_variants', [FrontendProductController::class, 'getDetailByAttrValue']);
    Route::get('/products_all', [FrontendProductController::class, 'productAll']);
    Route::post('/contact', [FrontendPageController::class, 'sendContactMessage']);
    Route::get('/search', [HomepageController::class, 'advancedSearch']);

    Route::middleware(['auth'])->group(function() {
        Route::get('/cart', [CartController::class, 'showCart']);
        Route::delete('/cart/{id}', [CartController::class, 'delete']);
        Route::post('/cart/adjust-qty/{id}', [CartController::class, 'adjustQty']);
        Route::post('/cart/add', [CartController::class, 'addCart']);
        Route::get('/wishlist', [CartController::class, 'showMyWhislist']);
        Route::get('/profile', [ProfileController::class, 'showMyProfile']);
        Route::get('/wishlist', [WishlistController::class, 'index']);
        Route::post('/wishlist', [WishlistController::class, 'addWishlist']);
        Route::post('/wishlist/cart', [WishlistController::class, 'addWishlistToCart']);
        Route::delete('/wishlist/{id}', [WishlistController::class, 'deleteWishlist']);
        Route::get('/count_message', [MessagesController::class, 'countUnseenMessage']);
    });
});

/* Dashboard */
Route::prefix('dashboard')->middleware(['auth', 'PermissionRoute'])->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/notifications', [DashboardController::class, 'notifications']);
    Route::get('/messages', [DashboardController::class, 'messages']);
    Route::resource('/profile', ProfileController::class);
    Route::group(['middleware' => ['permission:user']], function() {
        Route::resource('/staff', StaffController::class);
        Route::resource('/customer', CustomerController::class);
        Route::resource('/organization', OrganizationController::class);
        Route::get('/organization/type/{level}', [OrganizationController::class, 'getOrgByType']);
        Route::get('/permission/role/{role}', [PermissionController::class, 'getPermissionByRole']);
    });
    Route::group(['middleware' => ['permission:role']], function() {
        Route::resource('/role', RoleController::class);
        Route::resource('/permission', PermissionController::class);
    });
    Route::group(['middleware' => ['permission:product']], function() {
        Route::resource('category', CategoryController::class);
        Route::resource('attribute', AttributeController::class);
        Route::resource('unit', UnitTypeController::class);
        Route::resource('product', ProductController::class);
        Route::get('category/child/{id}', [CategoryController::class, 'getChildCategory']);
        Route::delete('media', [MediaController::class, 'destroy']);
        // Route::get('attribute/values', [AttributeController::class, 'getAttribute']);
        Route::get('attribute/values/{id}', [AttributeController::class, 'getValuesByAttribute']);
        Route::resource('product-review', ProductReviewController::class);
    });
    Route::group(['middleware' => ['permission:transaction']], function() {
        /* RFQ */
        Route::get('request-for-quotation', [QuotationController::class, 'requestForQuotationIndex'])->name('rfq.index');
        Route::get('request-for-quotation-form', [QuotationController::class, 'showRfqForm'])->name('rfq.create');
        Route::post('request-for-quotation-form', [QuotationController::class, 'submitRFQ'])->name('rfq.create');
        Route::delete('request-for-quotation', [QuotationController::class, 'deleteRFQ'])->name('rfq.delete');
        Route::get('request-for-quotation/{number}', [QuotationController::class, 'showQuotation'])->name('rfq.index');
        Route::get('request-for-quotation/{number}/update', [QuotationController::class, 'editRFQ'])->name('rfq.update');
        Route::post('request-for-quotation/{number}/reject', [QuotationController::class, 'rejectRFQ'])->name('rfq.update');
        Route::post('request-for-quotation/{number}', [QuotationController::class, 'submitQuotation'])->name('quotation.create');
        Route::delete('request-for-quotation/{number}', [QuotationController::class, 'deleteRFQ'])->name('rfq.update');

        /* Quotation */
        Route::get('quotation', [QuotationController::class, 'quotationIndex'])->name('quotation.index');
        Route::get('quotation/{number}/update', [QuotationController::class, 'editQuotation'])->name('quotation.update');
        Route::post('quotation/{number}/negotiation', [QuotationController::class, 'submitNegotiation']);
        Route::get('quotation/{number}', [QuotationController::class, 'showQuotation'])->name('quotation.create');
        Route::post('quotation/{number}/send-negotiate-message', [QuotationController::class, 'sendNegotiateMessage'])->name('quotation.create');
        Route::post('quotation/{number}/reject-negotiate', [QuotationController::class, 'rejectNegotiation'])->name('quotation.update');
        Route::post('quotation/{number}/reject', [QuotationController::class, 'rejectQuotation'])->name('quotation.update');
        Route::get('quotation/{number}/download', [QuotationController::class, 'reportQuotation'])->name('quotation.view');
        Route::get('qemail', [QuotationController::class, 'quotationEmail'])->name('quotation');
        
        /* Purchase Order */
        Route::get('purchase-order', [PurchaseOrderController::class, 'index'])->name('purchase-order.index');
        Route::post('purchase-order', [PurchaseOrderController::class, 'createPurchaseOrder'])->name('purchase-order.create');
        Route::post('purchase-order/confirm', [PurchaseOrderController::class, 'confirmPurchaseOrder'])->name('purchase-order.create');
        Route::post('purchase-order/reject', [PurchaseOrderController::class, 'rejectPurchaseOrder'])->name('purchase-order.index');
        Route::get('purchase-order/{number}', [PurchaseOrderController::class, 'showPurchaseOrder'])->name('purchase-order.index');
        Route::delete('purchase-order/{number}', [PurchaseOrderController::class, 'deletePurchaseOrder'])->name('purchase-order.delete');
        Route::get('purchase-order/{number}/download', [PurchaseOrderController::class, 'reportPurchaseOrder'])->name('purchase-order.view');
        
        /* Delivery Order */
        Route::post('shipping-order', [DeliveryOrderController::class, 'createDeliveryOrder'])->name('shipping-order.create');
        Route::get('shipping-order', [DeliveryOrderController::class, 'index'])->name('shipping-order.index');
        Route::get('shipping-order/{number}', [DeliveryOrderController::class, 'showDeliveryOrder'])->name('shipping-order.index');
        Route::post('shipping-order/confirm', [DeliveryOrderController::class, 'confirmDeliveryOrder'])->name('shipping-order.create');
        Route::get('shipping-order/{number}/download', [DeliveryOrderController::class, 'reportDeliveryOrder'])->name('shipping-order.view');
        Route::get('shipping-order-bast/{number}/download', [DeliveryOrderController::class, 'reportBAST'])->name('shipping-order.view');

        /* Invoice */
        Route::get('invoice', [InvoiceController::class, 'index'])->name('invoice.view');
        Route::post('invoice/payment', [InvoiceController::class, 'createPayment']);
        Route::get('invoice/{number}', [InvoiceController::class, 'showInvoice'])->name('invoice.view');
        Route::post('invoice/submit-payment', [InvoiceController::class, 'uploadPayment'])->name('invoice.view');
        Route::post('invoice/confirm-payment', [InvoiceController::class, 'confirmPayment'])->name('invoice.view');
        Route::get('invoice/{number}/download', [InvoiceController::class, 'reportInvoice'])->name('invoice.view');

        Route::get('address', [CustomerController::class, 'listAddress']);
        Route::get('address/{id}', [CustomerController::class, 'addressById']);
        Route::delete('address/{id}', [CustomerController::class, 'deleteAddress']);
        Route::put('address/{id}', [CustomerController::class, 'updateAddress']);
        Route::post('address', [CustomerController::class, 'saveAddress']);
        Route::get('province', [AreaController::class, 'province']);
        Route::get('city/{province_id}', [AreaController::class, 'getCitiesByProvince']);
        Route::get('district/{city_id}', [AreaController::class, 'getDistrictsByCity']);
        Route::get('subdistrict/{district_id}', [AreaController::class, 'getSubdistrictsByDistrict']);
        Route::post('purchase-order-documents', [PurchaseOrderController::class, 'uploadDocument']);
        Route::delete('purchase-order-documents/{id}', [PurchaseOrderController::class, 'deleteDocument']);
    });
    Route::group(['middleware' => ['permission:marketing']], function() {
        Route::resource('banner', BannerController::class);
        Route::resource('flash-deals', FlashDealController::class);
        Route::resource('coupon', CouponController::class);
    });
    Route::group(['middleware' => ['permission:shipping']], function() {
        Route::resource('shipping-method', ShippingController::class);
        Route::get('shipping-method-export', [ShippingController::class, 'exportShippingList'])->name('shipping-method.view');
        Route::post('shipping-method-import', [ShippingController::class, 'importShippingList']);
        Route::get('shipping-fee/{id}', [ShippingController::class, 'getShippingFeeById']);
        Route::post('shipping-fee/{id}', [ShippingController::class, 'updateShippingFee']);
    });
    Route::group(['middleware' => ['permission:payment']], function() {
        Route::resource('payment-method', PaymentController::class);
    });
    Route::group(['middleware' => ['permission:merchant']], function() {
        Route::resource('merchant', MerchantController::class);
    });
    Route::group(['namespace' => '\Rap2hpoutre\LaravelLogViewer'], function() {
        Route::get('logs', 'LogViewerController@index')->name('log.index');
    });
    Route::group(['middleware' => ['permission:setting']], function() {
        Route::get('general-setting', [GeneralSettingController::class, 'showGeneralSetting'])->name('general-setting.view');
        Route::get('company', [GeneralSettingController::class, 'showCompanyInformation'])->name('company-information.view');
        Route::get('homepage', [GeneralSettingController::class, 'showHomepageSEO'])->name('homepage-seo.view');
        Route::put('general-setting', [GeneralSettingController::class, 'save'])->name('general-setting.view');
        Route::resource('vat', VATController::class);
    });
    Route::group(['middleware' => ['permission:frontend']], function() {
        Route::resource('frontend', PageController::class);
    });
    Route::group(['middleware' => ['permission:audit']], function() {
        Route::resource('audit', AuditController::class);
    });
    Route::post('media', [MediaController::class, 'store']);
});

Route::get('/{pages}', [FrontendPageController::class, 'showPage']);