<?php

use App\Http\Controllers\AdminTransactionController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CompaniesController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\EmployeeStageController;
use App\Http\Controllers\Api\EOSController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\ModeratorsController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\PaymentAccountController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\Api\ReportsController;
use App\Http\Controllers\Api\SalaryController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WalletController;
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

Route::post('/payment/webhook', [WalletController::class, 'handleWebhook']);
Route::get('/payment/result', [WalletController::class, 'handleShopperResult'])->name('payment.result');

Route::group([
    'middleware' => ['auth:sanctum','throttle:100,1'],
],function(){
    Route::post('logout',[UserAuthController::class  , 'logout']);
    Route::get('user',[UserAuthController::class , 'user']);
    Route::post('send-verification-code',[OtpController::class , 'send']);
    Route::post('verify-code',[OtpController::class , 'verify']);

//    Route::apiResource('companies', CompaniesController::class)->only(['index', 'show']);
    Route::get('companies/profile', [CompaniesController::class, 'getCompanyProfile']);
//        ->middleware('role:super-admin|admin');
    Route::apiResource('employees', EmployeeController::class)->only(['index', 'show']);
    Route::apiResource('leaves', LeaveController::class)->only(['index', 'show']);
    Route::apiResource('payment-accounts', PaymentAccountController::class)->only(['index', 'show']);
    Route::apiResource('eos', EOSController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

    Route::apiResource('wallets', WalletController::class)->only(['index', 'show']);
    Route::controller(WalletController::class)->group(function () {
        Route::post('wallet/charge', 'chargeWallet')->name('wallet.charge');
    });


    Route::apiResource('admins', AdminController::class)->middleware('role:super-admin|admin')->except(['update']);
    Route::post('/admins/update/{id}', [AdminController::class, 'update'])->middleware('role:super-admin|admin');
    Route::apiResource('moderators', ModeratorsController::class)->middleware('role:super-admin|admin')->except(['update']);
    Route::post('/moderators/update/{id}', [ModeratorsController::class, 'update'])->middleware('role:super-admin|admin');

    Route::group([
        'controller' => SalaryController::class,
    ], function () {
       Route::get('/salaries', 'index')->name('salaries.index');
       Route::post('/salaries/generate', 'generate')->name('salaries.generate');
       Route::post('/salaries/paySalary', 'paySalary')->name('salaries.pay');
       Route::post('/salaries/bulkPaySalaries', 'bulkPaySalaries')->name('salaries.bulkPaySalaries');
       Route::delete('/salaries/{id}', 'destroy')->name('salaries.destroy');
    });


    Route::resource('users', UserController::class)->except(['show', 'create', 'edit', 'update'])
        ->names([
            'index'     => 'users.index',
            'destroy'   => 'users.destroy',
        ]);

    Route::put('users/{id}/toggle', [UserController::class, 'toggleStatus'])
        ->name('users.toggle');
//    Route::post('users/export', [UserController::class, 'export'])
//        ->name('users.export');


    Route::prefix('employee-stages')
        ->controller(EmployeeStageController::class)
        ->group(function () {
            Route::get('/single-employee', 'getSingleEmployee');
            Route::get('/pending', 'getPending');
            Route::get('/{employeeStageId}', 'show');
            Route::get('/{employeeStageId}/payment-page', 'showPayEmployeeStagePage');
            Route::post('/pay', 'payEmployeeStage');

        // Placeholder routes for future implementation
//        Route::post('/', [EmployeeStageController::class, 'store']);
//        Route::put('/{employeeStageId}', [EmployeeStageController::class, 'update']);
//        Route::delete('/{employeeStageId}', [EmployeeStageController::class, 'destroy']);
    });

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

    Route::group([
        'controller' => ReportsController::class,
        'prefix' => 'reports',
        'middleware' => ['auth:sanctum'],
    ], function () {
        Route::get('employees', 'getEmployeesReport');
        Route::get('end-of-services', 'getEOSReport');
        Route::get('leaves', 'getLeavesReport');
        Route::get('employees-salaries', 'getEmployeesSalaryReport');
    });
});



