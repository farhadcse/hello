<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OptController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShipperController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\OrderManageController;
use App\Http\Controllers\OrderPurchaseController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SupplierController;


use App\Http\Controllers\WCProductController;
use App\Http\Controllers\WCOrderPurchaseController;
use App\Http\Controllers\WcRestDataCountriesController;

// Route::get('/', [AuthenticatedSessionController ::class, 'create'])->name('front page');
Route::get('/', [TrackController::class, 'index'])->name('front page');
Route::post('/', [TrackController::class, 'getData']);
// Route::get('tes', [TrackController::class, 'getData']);
Route::get('/token', function () {
    return csrf_token();
});

    Route::group(['middleware' => ['auth']], function () {

    Route::middleware(['staff'])->group(function () {

        Route::get('/staff', function () {
            return redirect(route('dashboard'));
        });
        Route::get('staff/dashboard', [StaffController::class, 'dashboard'])->name('staff dashboard');
        Route::get('quantity-update', [StaffController::class, 'quantityUpdate'])->name('quantity update');
        Route::get('staff/product/data', [StaffController::class, 'productData'])->name('product data staff');
        Route::post('staff/product/update', [StaffController::class, 'productUpdate'])->name('check in check out');
        Route::get('staff/see-details/{id}', [StaffController::class, 'seeDetails'])->name('see quantity details');

    });


    Route::middleware(['member'])->group(function () {

        Route::get('/seller', function () {
            return redirect(route('dashboard'));
        });
        Route::get('/dashboard', [SellerController::class, 'dashboard'])->name('dashboard');
        Route::get('/chart-data', [SellerController::class, 'chartData']);
        Route::get('/seller/manage-tracking', [TrackingController::class, 'index'])->name('manage tracking');
        Route::get('/tracking/data', [TrackingController::class, 'data'])->name('data tracking');
        Route::post('/tracking/insert', [TrackingController::class, 'store'])->name('insert tracking');
        Route::post('/tracking/import', [TrackingController::class, 'import'])->name('import tracking');
        Route::post('/tracking/update', [TrackingController::class, 'update'])->name('update order');
        Route::post('/tracking/delete', [TrackingController::class, 'delete'])->name('delete tracking');
        Route::get('/track-page', [TrackingController::class, 'trackPage'])->name('track page');
        //Tracking
        Route::post('/track-id', [SellerController::class, 'TrackId'])->name('Track Id');
        //product
        Route::get('/product', [ProductController::class, 'product'])->name('product');
        Route::get('/product/data', [ProductController::class, 'data'])->name('data product');
        Route::get('/product/data_purchase_order', [ProductController::class, 'dataPurchaseOrder'])->name('data purchase order');
        Route::post('/product/insert', [ProductController::class, 'insert'])->name('insert product');
        Route::post('/product/update', [ProductController::class, 'update'])->name('update product');
        Route::post('/product/delete', [ProductController::class, 'delete'])->name('delete product');
        Route::post('/product/bulk-import', [ProductController::class, 'bulkImport'])->name('product_bulk_import');
        //staff
        Route::get('manage-staff', [StaffController::class, 'manageStaff'])->name('manage staff');
        Route::get('staff/data', [StaffController::class, 'data'])->name('data staff');
        Route::post('staff/insert', [StaffController::class, 'insert'])->name('insert staff');
        Route::post('staff/update', [StaffController::class, 'update'])->name('update staff');
        Route::post('staff/delete', [StaffController::class, 'delete'])->name('delete staff');
        //quantity update
        Route::get('seller/product/data', [ProductController::class, 'productData'])->name('product data seller');
        Route::post('seller/product/update', [ProductController::class, 'productUpdate'])->name('seller quantity update');
        Route::get('seller/see-details/{id}', [ProductController::class, 'seeDetails'])->name('seller quantity details');

        Route::get('/date-quantity-log', [ProductController::class, 'dataQuantityLog'])->name('date quantity log');
        Route::post('/update-quantity-log', [ProductController::class, 'updateQuantityLog'])->name('update quantity log');
        Route::post('/delete-quantity-log', [ProductController::class, 'deleteQuantityLog'])->name('delete quantity log');

        // Route::get('/purchase_order', [PurchaseOrderController::class, 'index'])->name('purchase order');
        Route::resource('order_management', OrderManageController::class);
        Route::post('order_management_update/{id}', [OrderManageController::class,'orderManagementUpdate']);
        Route::get('order_management-delete/{id}', [OrderManageController::class,'orderManagementDelete']);

        //seetings

        //categories
        Route::resource('categories', CategoryController::class);
        Route::POST('categories_update/{id}',[ CategoryController::class,'updateCategory']);
        Route::get('/categories-delete/{id}', [CategoryController::class, 'delete']);

        //supplier
        Route::resource('suppliers', SupplierController::class);
        Route::POST('suppliers_update/{id}',[ SupplierController::class,'updateSupplier']);
        Route::get('/suppliers-delete/{id}', [SupplierController::class, 'delete']);

        //shop
        Route::resource('shops', ShopController::class);
        Route::POST('shops_update/{id}',[ ShopController::class,'updateShop']);
        Route::get('/shops-delete/{id}', [ShopController::class, 'delete']);

        //oder purchase
        Route::resource('order_purchase', OrderPurchaseController::class);
        Route::post('order_purchase_update/{id}', [OrderPurchaseController::class,'orderPurchaseUpdate']);
        Route::get('order_purchase-delete/{id}', [OrderPurchaseController::class,'orderPurchaseDelete']);
        Route::post('change_otder_purchase_status', [OrderPurchaseController::class,'changeOrderPurchaseStatus']);

        Route::get('/get_qr_code', [QrCodeController::class, 'get_qr_code']);

   });

    Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin dashboard');
        Route::get('manage-seller', [AdminController::class, 'manageSeller'])->name('manage seller');
        Route::get('seller/data', [AdminController::class, 'data'])->name('data seller');
        Route::post('seller/insert', [AdminController::class, 'insert'])->name('insert seller');
        Route::post('seller/update', [AdminController::class, 'update'])->name('update seller');
        Route::post('seller/delete', [AdminController::class, 'delete'])->name('delete seller');
        Route::get('manage-shipper', [ShipperController::class, 'index'])->name('manage shipper');
        Route::get('shipper/data', [ShipperController::class, 'data'])->name('data shipper');
        Route::post('shipper/insert', [ShipperController::class, 'insert'])->name('insert shipper');
        Route::post('shipper/update', [ShipperController::class, 'update'])->name('update shipper');
        Route::post('shipper/delete', [ShipperController::class, 'delete'])->name('delete shipper');
        Route::get('/user-logo', [AdminController::class, 'userLogo'])->name('user logo');
        Route::post('/user-logo-update', [AdminController::class, 'uploadUserLogo'])->name('upload user logo');
         //package
         Route::get('/package', [SellerController::class, 'package'])->name('package');
         Route::get('/package/data', [SellerController::class, 'data'])->name('data package');
         Route::post('/package/insert', [SellerController::class, 'insert'])->name('insert package');
         Route::post('/package/update', [SellerController::class, 'update'])->name('update package');
         Route::post('/package/delete', [SellerController::class, 'delete'])->name('delete package');
         //trackinglog
         Route::get('seller/tracking-log/{id}', [AdminController::class, 'trackingLog'])->name('seller tracking log');

    });

    //gerereate qr code
    Route::get('/generate-qr-code', [QrCodeController::class, 'generateQrCode'])->name('generate qr code');

    Route::get('/view-qr-code/{id}', [QrCodeController::class, 'viewQrCode'])->name('view qr code');
    Route::post('/add-product-code', [QrCodeController::class, 'addProductCode'])->name('add product_code');
    Route::post('/generate_qr_code_pdf', [QrCodeController::class, 'generateQrCodePdf'])->name('print qr code');
    Route::get('/in-out', [QrCodeController::class, 'inOutWithQrCode'])->name('inout qr code');
    Route::get('/get-qr-code-product', [QrCodeController::class, 'getQrCodeProduct'])->name('get_qr_code_product');
    Route::get('/get-qr-code-productget-order-purchase', [QrCodeController::class, 'getQrCodeProductForOrderPurchase'])->name('get_qr_code_product_order_purchase');
    Route::get('/get-qr-code-productget-order_managment', [QrCodeController::class, 'getQrCodeProductForOrderManagment'])->name('get_qr_code_product_order_managment');
    Route::get('/reset-qr-code-product', [QrCodeController::class, 'resetQrCodeProduct'])->name('reset_session_product');
    Route::get('/delete-session-product', [QrCodeController::class, 'deleteSessionProduct'])->name('delete_session_product');
    Route::get('/delete-session-product2', [QrCodeController::class, 'deleteSessionProduct2'])->name('delete_session_product2');
    Route::post('/submit-input', [QrCodeController::class, 'updateInOut'])->name('submit input');
    Route::post('/autocomplete/getAutocomplete/',[QrCodeController::class, 'getAutocomplete'])->name('Autocomplte.getAutocomplte');
    Route::get('/your_packages', [AccountController::class, 'yourPackages'])->name('your_packages');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::post('/profile-update', [AccountController::class, 'profileUpdate']);
    Route::post('/change-password', [AccountController::class, 'changePassword'])->name('change password');
});
Route::get('verify_mobile', [OptController::class, 'verifyMobile'])->name('verify_mobile');
Route::post('/get-otp', [OptController::class, 'getOtp'])->name('get-otp');
Route::post('/reset-pass', [OptController::class, 'resetpass'])->name('reset-pass');
Route::post('/get-phone', [OptController::class, 'getPhone'])->name('get-phone');
Route::get('/forget_password', [OptController::class, 'forgetPassword']);
Route::get('/reset_password', [OptController::class, 'resetPassword']);
require __DIR__ . '/auth.php';



 //WC Controller
 //WC Controller
 //WC Controller

 Route::get('/wc_products', [WCProductController::class, 'product'])->name('wc_products');
 Route::post('/wc_products/getPaginationData', [WCProductController::class, 'getPaginationData'])->name('wc_products pagination');
 Route::get('/wc_products/wc_data_product', [WCProductController::class, 'wc_data_product'])->name('wc_data_product');
 Route::get('/product/data_purchase_order', [WCProductController::class, 'dataPurchaseOrder'])->name('data purchase order');
 Route::post('/product/insert', [WCProductController::class, 'insert'])->name('insert product');
 Route::post('/wc_products/wc_product_update', [WCProductController::class, 'wc_product_update'])->name('wc_product_update');
 Route::post('/wc_products/wc_product_delete', [WCProductController::class, 'wc_product_delete'])->name('wc_product_delete');
 Route::post('/product/bulk-import', [WCProductController::class, 'bulkImport'])->name('product_bulk_import');


 //oder purchase
 Route::get('/wc_order_purchase/getCustomerAddress', [WCOrderPurchaseController::class, 'getCustomerAddress'])->name('data customer address');
 Route::get('/wc_order_purchase/getOrderProducts', [WCOrderPurchaseController::class, 'getOrderProducts'])->name('data order products');
 Route::get('/wc_order_purchase/getOrderStatus', [WCOrderPurchaseController::class, 'getOrderStatus'])->name('data order status');
 Route::post('/wc_order_purchase/bulkStatus', [WCOrderPurchaseController::class, 'bulkStatus'])->name('data bulkStatus');
 Route::post('changeOrderPurchaseStatus', [WCOrderPurchaseController::class,'changeOrderPurchaseStatus'])->name('wc_change_order_purchase_status');
 Route::get('/wc_order_purchase/data', [WCOrderPurchaseController::class, 'data'])->name('data order');
 Route::get('/wc_order_purchase/sync', [WCOrderPurchaseController::class, 'sync'])->name('sync');
 Route::post('/wc_order_purchase/getCountryStateSyncData', [WCOrderPurchaseController::class, 'getCountryStateSyncData'])->name('wc_country_state_sync');
 Route::resource('wc_order_purchase', WCOrderPurchaseController::class);
 Route::post('/wc_order_purchase/getOrderSyncData', [WCOrderPurchaseController::class, 'getOrderSyncData'])->name('wc_orders_sync');

 Route::post('/wc_order_purchase/wc_order_delete', [WCOrderPurchaseController::class, 'wc_order_delete'])->name('wc_order_delete');

 Route::post('order_purchase_update/{id}', [WCOrderPurchaseController::class,'orderPurchaseUpdate']);
 Route::get('order_purchase-delete/{id}', [WCOrderPurchaseController::class,'orderPurchaseDelete']);

 Route::post('wc_change_order_address', [WCOrderPurchaseController::class,'changeOrderPurchaseAddress']);
