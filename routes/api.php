<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->group(function () {
    //Authentication api
    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/logout', [AdminController::class, 'logout'])->middleware('auth:api-admin');
    Route::post('/forget-password', [AdminController::class, 'forgotPassword']);
    Route::post('/reset-password', [AdminController::class, 'resetPassword']);
    Route::post('/change-password', [AdminController::class, 'changePassword'])->middleware('auth:api-admin');

    //Category CRUD
    Route::prefix('categories')->group(function () {
        Route::get('/', [AdminController::class, 'getAllCategories'])->middleware('auth:api-admin');
        Route::post('/', [AdminController::class, 'addNewCategory'])->middleware('auth:api-admin');
        Route::get('/{id}', [AdminController::class, 'getCategory'])->middleware('auth:api-admin')->whereNumber('id');
        Route::put('/{id}', [AdminController::class, 'updateCategory'])->middleware('auth:api-admin')->whereNumber('id');
        Route::delete('/{id}', [AdminController::class, 'deleteCategory'])->middleware('auth:api-admin')->whereNumber('id');
        //SubCategory CRUD
        Route::get('/{category_id}/sub-categories', [AdminController::class, 'getAllSubcategoriesBelongMainCategory'])->middleware('auth:api-admin')->whereNumber('category_id');
        Route::get('/{category_id}/sub-categories/{sub_category_id}', [AdminController::class, 'getSubCategoryBelongMainCategory'])
            ->middleware('auth:api-admin')
            ->whereNumber('category_id')
            ->whereNumber('sub_category_id');
        Route::post('/{category_id}/sub-categories', [AdminController::class, 'createNewSubCategory'])->middleware('auth:api-admin')->whereNumber('category_id');
        Route::post('/{category_id}/sub-categories/{sub_category_id}', [AdminController::class, 'updateSubCategory'])
            ->middleware('auth:api-admin')
            ->whereNumber('category_id')
            ->whereNumber('sub_category_id');
        Route::delete('/{category_id}/sub-categories/{sub_category_id}', [AdminController::class, 'deleteSubCategory'])
            ->middleware('auth:api-admin')
            ->whereNumber('category_id')
            ->whereNumber('sub_category_id');
        //ChildCategory CRUD
        Route::get('/{category_id}/sub-categories/{sub_category_id}/child-categories', [AdminController::class, 'getAllChilldCategoriesBelongsSubCategory'])
            ->middleware('auth:api-admin')
            ->whereNumber('category_id')
            ->whereNumber('sub_category_id');
        Route::get('/{category_id}/sub-categories/{sub_category_id}/child-categories/{child_category_id}', [AdminController::class, 'getChildCategoryBelongsSubCategory'])
            ->middleware('auth:api-admin')
            ->whereNumber('category_id')
            ->whereNumber('sub_category_id')
            ->whereNumber('child_category_id');
        Route::post('/{category_id}/sub-categories/{sub_category_id}/child-categories', [AdminController::class, 'addNewChilldCategory'])
            ->middleware('auth:api-admin')
            ->whereNumber('category_id')
            ->whereNumber('sub_category_id');
        Route::post('/{category_id}/sub-categories/{sub_category_id}/child-categories/{child_category_id}', [AdminController::class, 'updateChilldCategory'])
            ->middleware('auth:api-admin')
            ->whereNumber('category_id')
            ->whereNumber('sub_category_id')
            ->whereNumber('child_category_id');
        Route::delete('/{category_id}/sub-categories/{sub_category_id}/child-categories/{child_category_id}', [AdminController::class, 'deleteChilldCategory'])
            ->middleware('auth:api-admin')
            ->whereNumber('category_id')
            ->whereNumber('sub_category_id')
            ->whereNumber('child_category_id');
    });
    //Brand CRUD
    Route::prefix('brands')->group(function () {
        Route::get('/', [AdminController::class, 'getAllBrands'])->middleware('auth:api-admin');
        Route::get('/{brand_id}', [AdminController::class, 'getBrand'])->middleware('auth:api-admin');
        Route::post('/', [AdminController::class, 'addNewBrand'])->middleware('auth:api-admin');
        Route::post('/{brand_id}', [AdminController::class, 'updateBrand'])->middleware('auth:api-admin');
        Route::delete('/{brand_id}', [AdminController::class, 'deleteBrand'])->middleware('auth:api-admin');
    });
    //SEO CRUD
    Route::prefix('seos')->group(function () {
        Route::get('/', [AdminController::class, 'getAllSeo'])->middleware('auth:api-admin');
        Route::get('/{id}', [AdminController::class, 'getSeo'])->middleware('auth:api-admin')->whereNumber('id');
        Route::post('/', [AdminController::class, 'addNewSeo'])->middleware('auth:api-admin');
        Route::put('/{id}', [AdminController::class, 'updateSeo'])->middleware('auth:api-admin')->whereNumber('id');
        Route::delete('/{id}', [AdminController::class, 'deleteSeo'])->middleware('auth:api-admin')->whereNumber('id');
    });
    //SMTP api
    Route::prefix('smtp')->group(function () {
        Route::post('/{smtp_id}', [AdminController::class, 'updateSmtp'])
            ->middleware('auth:api-admin')
            ->whereNumber('smtp_id');
    });
    //Setting api
    Route::prefix('settings')->group(function () {
        Route::get('/', [AdminController::class, 'getAllSettings'])->middleware('auth:api-admin');
        Route::post('/{setting_id}', [AdminController::class, 'updateSetting'])
            ->middleware('auth:api-admin')
            ->whereNumber('setting_id');
    });
    // Wharehouse api
    Route::prefix('wharehouses')->group(function () {
        Route::get('/', [AdminController::class, 'gettAllWharehouses'])->middleware('auth:api-admin');
        Route::get('/{wharehouse_id}', [AdminController::class, 'getWharehoyuse'])
            ->middleware('auth:api-admin')
            ->whereNumber('wharehouse_id');
        Route::post('/', [AdminController::class, 'createWharhouse'])->middleware('auth:api-admin');
        Route::post('/{wharehouse_id}', [AdminController::class, 'updateWharehouse'])
            ->middleware('auth:api-admin')
            ->whereNumber('wharehouse_id');
        Route::delete('/{wharehouse_id}', [AdminController::class, 'deleteWharehouse'])
            ->middleware('auth:api-admin')
            ->whereNumber('wharehouse_id');
    });
    // PickupPoint api
    Route::prefix('pickup-points')->group(function () {
        Route::get('/', [AdminController::class, 'getAllPickupPoint'])->middleware('auth:api-admin');
        Route::get('/{id}', [AdminController::class, 'getPickupPoint'])->middleware('auth:api-admin')->whereNumber('id');
        Route::post('/', [AdminController::class, 'addNewPickuPoint'])->middleware('auth:api-admin');
        Route::post('/{id}', [AdminController::class, 'updatePickupPoint'])->middleware('auth:api-admin')->whereNumber('id');
        Route::delete('/{id}', [AdminController::class, 'deletePickupPoint'])->middleware('auth:api-admin')->whereNumber('id');
    });
    // Coupon api
    Route::prefix('coupons')->group(function () {
        Route::get('/', [AdminController::class, 'getAllCoupon'])->middleware('auth:api-admin');
        Route::get('/{coupon_id}', [AdminController::class, 'getCoupon'])
            ->middleware('auth:api-admin')
            ->whereNumber('coupon_id');
        Route::post('/', [AdminController::class, 'addNewCoupon'])->middleware('auth:api-admin');
        Route::post('/{coupon_id}', [AdminController::class, 'updateCoupon'])
            ->middleware('auth:api-admin')
            ->whereNumber('coupon_id');
        Route::delete('/{coupon_id}', [AdminController::class, 'deleteCoupon'])
            ->middleware('auth:api-admin')
            ->whereNumber('coupon_id');
    });
    // Pages api
    Route::prefix('pages')->group(function () {
        Route::get('/', [AdminController::class, 'getAllPages'])->middleware('auth:api-admin');
        Route::get('/{page_id}', [AdminController::class, 'getPage'])->middleware('auth:api-admin')->whereNumber('page_id');
        Route::post('/', [AdminController::class, 'addNewPage'])->middleware('auth:api-admin');
        Route::post('/{page_id}', [AdminController::class, 'updatePage'])->middleware('auth:api-admin')->whereNumber('page_id');
        Route::delete('/{page_id}', [AdminController::class, 'deletePage'])->middleware('auth:api-admin')->whereNumber('page_id');
    });
    // Products api
    Route::prefix('products')->group(function () {
        Route::get('/', [AdminController::class, 'getAllProducts'])->middleware('auth:api-admin');
        Route::post('/', [AdminController::class, 'addNewProduct'])->middleware('auth:api-admin');
        Route::post('/{product_id}', [AdminController::class, 'updateProduct'])->middleware('auth:api-admin')->whereNumber('product_id');
        Route::delete('/{product_id}', [AdminController::class, 'deleteProduct'])->middleware('auth:api-admin')->whereNumber('product_id');
        Route::get('/{product_id}/active-status', [AdminController::class, 'activeStatus'])
            ->middleware('auth:api-admin')
            ->whereNumber('product_id');
        Route::get('/{product_id}/dis-active-status', [AdminController::class, 'disActiveStatus'])
            ->middleware('auth:api-admin')
            ->whereNumber('product_id');
        Route::get('/{product_id}/active-featured', [AdminController::class, 'activeFeatured'])
            ->middleware('auth:api-admin')
            ->whereNumber('product_id');
        Route::get('/{product_id}/dis-active-featured', [AdminController::class, 'disActiveFeatured'])
            ->middleware('auth:api-admin')
            ->whereNumber('product_id');
        Route::get('/{product_id}/active-product-slider', [AdminController::class, 'activeProductSlider'])
            ->middleware('auth:api-admin')
            ->whereNumber('product_id');
        Route::get('/{product_id}/dis-active-product-slider', [AdminController::class, 'disActiveProductSlider'])
            ->middleware('auth:api-admin')
            ->whereNumber('product_id');
        Route::get('/{product_id}/active-today-deal', [AdminController::class, 'activeTodayDeal'])
            ->middleware('auth:api-admin')
            ->whereNumber('product_id');
        Route::get('/{product_id}/dis-active-today-deal', [AdminController::class, 'disActiveTodayDeal'])
            ->middleware('auth:api-admin')
            ->whereNumber('product_id');
        Route::post('/filtering_products', [AdminController::class, 'productsFilter'])
            ->middleware('auth:api-admin');
    });
});
// Customers api
Route::prefix('customer')->group(function () {
    //Authentication api
    Route::post('/register', [CustomerController::class, 'register']);
    Route::post('/login', [CustomerController::class, 'login']);
    Route::post('/logout', [CustomerController::class, 'logout'])->middleware('auth:api-customer');
    Route::post('/forget-password', [CustomerController::class, 'forgotPassword']);
    Route::post('/reset-password', [CustomerController::class, 'resetPassword']);
});
