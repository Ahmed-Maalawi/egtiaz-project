<?php

use App\Http\Controllers\AdminTransactionController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CompaniesController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\EOSController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\PaymentAccountController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\UserAuthController;
use App\Models\PaymentAccount;
use Illuminate\Support\Facades\Route;

Route::group([
    'controller' => AdminTransactionController::class,
    'prefix' => 'transactions',
], function () {
    Route::post('/', 'store');
});

Route::post('register', [UserAuthController::class, 'register'])->middleware('throttle:10,1');
Route::post('login', [UserAuthController::class, 'login'])->middleware('throttle:5,1');
Route::get('cities', [CityController::class, 'allCities'])->middleware('throttle:30,1');
Route::get('cities/search', [CityController::class, 'search'])->middleware('throttle:30,1');
Route::get('banners', [BannerController::class, 'index'])->middleware('throttle:30,1');
Route::post('forget-password', [OtpController::class, 'forgotPassword'])->middleware('throttle:30,1');
Route::post('reset-password', [OtpController::class, 'resetPassword'])->middleware('throttle:30,1');

Route::group([
    'middleware'            =>['auth:sanctum','throttle:100,1'],
],function(){
    Route::post('logout',[UserAuthController::class  , 'logout']);
    Route::get('user',[UserAuthController::class , 'user']);
    Route::post('send-verification-code',[OtpController::class , 'send']);
    Route::post('verify-code',[OtpController::class , 'verify']);

    Route::apiResource('companies', CompaniesController::class)->only(['index', 'show']);
    Route::apiResource('employees', EmployeeController::class)->only(['index', 'show']);
    Route::apiResource('leaves', LeaveController::class)->only(['index', 'show']);
    Route::apiResource('payment-accounts', PaymentAccountController::class)->only(['index', 'show']);
    Route::apiResource('eos', EOSController::class)->only(['index', 'show']);

    Route::group([
        'middleware' => [
            'phone-verified-sanctum',
            'user-unbanned'
        ],
    ],function(){
        Route::put('update',[UserAuthController::class , 'update']);
        Route::put('update-tokens',[UserAuthController::class , 'updateTokens']);
        Route::delete('delete-account',[UserAuthController::class , 'deleteAccount']);
        Route::get('my-qrcode',[UserAuthController::class , 'myQrcode']);
        Route::get('my-referral-code',[UserAuthController::class , 'myReferralCode']);
        Route::get('my-card',[UserAuthController::class , 'myCard']);
        //----------------------------------------------------------
        Route::get('main-categories',[CategoryController::class , 'mainCategories']);
        Route::get('sub-categories-by-main',[CategoryController::class , 'subCategories']);
        Route::get('providers-by-categories',[CategoryController::class , 'providersByCategory']);
        //----------------------------------------------------------
        Route::get('all-services',[ServiceController::class , 'allServices']);
        Route::get('popular-services',[ServiceController::class , 'popularServices']);
        Route::get('providers-by-service',[ServiceController::class , 'providersByService']);
        //--------------------------------------------------------
        Route::get('single-provider',[ProviderController::class , 'singleProvider']);
        Route::get('search-providers',[ProviderController::class , 'search']);
        Route::get('liked-providers',[ProviderController::class , 'myProviders']);
        Route::post('providers-toggle',[ProviderController::class , 'likeToggle']);
        Route::post('provider-review/store',[ProviderController::class , 'addReview']);
        Route::post('providers/store',[ProviderController::class , 'store']);
        Route::get('my-providers',[ProviderController::class , 'providersOwned']);
        Route::put('providers/{serviceProvider}/update',[ProviderController::class , 'update']);
        //----------------------------------------------------
        Route::post('create-order',[OrderController::class , 'create']);
        Route::put('order/{id}/confirm-from-cashier',[OrderController::class , 'confirmOrderFromCashier']);
        Route::put('order/{id}/confirm-from-user',[OrderController::class , 'confirmOrderFromUser']);
        Route::get('my-orders',[OrderController::class , 'myOrders']);
        Route::get('pending-orders-for-user',[OrderController::class , 'pendingOrders']);
        //-------------------------------------------------------
        Route::get('nearby-providers',[ProviderController::class , 'nearbyProvider']);

    });
});



