<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\CompanyController;
use App\Http\Controllers\Dashboard\BannerController;
use App\Http\Controllers\Dashboard\CompanyModeratorsController;
use App\Http\Controllers\Dashboard\EndOfServiceController;
use App\Http\Controllers\Dashboard\LeaveController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\EmployeeStageController;
use App\Http\Controllers\Dashboard\LeaveTypesController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\GeneralSettingController;
use App\Http\Controllers\Dashboard\IqamaTypeController;
use App\Http\Controllers\Dashboard\PaymentAccountController;
use App\Http\Controllers\Dashboard\ReportController;
use App\Http\Controllers\Dashboard\RolesController;
use App\Http\Controllers\Dashboard\SalariesController;
use App\Http\Controllers\Dashboard\SendPushNotification;
use App\Http\Controllers\Dashboard\StageController;
use App\Http\Controllers\Dashboard\TransactionController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'as'            => 'admins.',
    'prefix'        => 'admin/',
    'middleware'    => ['auth', 'locale']
], function () {
    Route::get('edit-profile', [ProfileController::class, 'editProfile'])->name('profile.edit');
    Route::put('edit-profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('edit-password', [ProfileController::class, 'editPassword'])->name('password.edit');
    Route::put('edit-password', [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::get('transactions', [TransactionController::class , 'index'])->name('transactions.index');

    Route::controller(RolesController::class)->group(function () {
        Route::get('/roles', 'index')->name('roles.index');
        Route::post('/roles', 'store')->name('roles.store');
        Route::get('/permissions', 'permissionsIndex')->name('roles.permissions');
        Route::post('/permissions/update', 'updatePermissions')->name('roles.permissions.update');
        Route::post('/roles/assign', 'assignRole')->name('roles.assign');
        Route::delete('/roles/{id}', 'destroy')->name('roles.destroy');
        Route::delete('/roles/remove/{user}/{role}', 'removeRole')->name('roles.remove');
    });
    //--------------------------------------------------

    Route::post('/language-switch', [GeneralSettingController::class, 'switchLanguage'])->name('language.switch');

    //---------------------------------------------------

    Route::resource('banners', BannerController::class)->except('show')
         ->middleware('role-or-permission:super-admin,banners')
        ->names([
            'index'    => 'banners.index',
            'create'   => 'banners.create',
            'store'    => 'banners.store',
            'edit'     => 'banners.edit',
            'update'   => 'banners.update',
            'destroy'  => 'banners.destroy',
        ]);

    //----------------------------------------------------------------

    Route::resource('companies', CompanyController::class)
        ->middleware('role-or-permission:super-admin,companies')
        ->names([
            'index'     => 'companies.index',
            'create'    => 'companies.create',
            'store'     => 'companies.store',
            'show'      => 'companies.show',
            'edit'      => 'companies.edit',
            'update'    => 'companies.update',
            'destroy'   => 'companies.destroy',
        ]);
    Route::put('companies/{id}/toggle', [CompanyController::class, 'toggleStatus'])
        ->middleware('role-or-permission:super-admin,companies')
        ->name('companies.toggle');

    Route::get('companies/company/search', [CompanyController::class, 'search'])
        ->middleware('role-or-permission:super-admin,companies')
        ->name('companies.search');

    // Get transaction details via AJAX
    Route::get('/admin/transactions/{type}/{id}/details', [CompanyController::class, 'getTransactionDetails'])
        ->middleware('role-or-permission:super-admin,companies')
        ->name('transactions.details');

    // Download invoice
    Route::get('/invoice/download/{transaction}', [CompanyController::class, 'downloadInvoice'])
        ->middleware('role-or-permission:super-admin,companies')
        ->name('invoice.download');


    Route::get('/admin/companies/{company}/download-report', [CompanyController::class, 'generateCompanyReport'])
        ->name('companies.download-report');

    // Export transactions
    Route::get('/companies/{company}/export-transactions', [CompanyController::class, 'exportTransactions'])
        ->middleware('role-or-permission:super-admin,companies')
        ->name('companies.export-transactions');

    // Profit report
    Route::get('/companies/{company}/profit-report', [CompanyController::class, 'profitReport'])
        ->middleware('role-or-permission:super-admin,companies')
        ->name('companies.profit-report');

    //------------------------------------------------------------

    Route::resource('iqama-types', IqamaTypeController::class)->except('show')
        ->names([
            'index'    => 'types.index',
            'create'   => 'types.create',
            'store'    => 'types.store',
            'edit'     => 'types.edit',
            'update'   => 'types.update',
            'destroy'  => 'types.destroy',
        ]);

    //------------------------------------------------------------

    Route::resource('employees', EmployeeController::class)
        ->names([
            'index'     => 'employees.index',
            'create'    => 'employees.create',
            'store'     => 'employees.store',
            'show'      => 'employees.show',
            'edit'      => 'employees.edit',
            'update'    => 'employees.update',
            'destroy'   => 'employees.destroy',
        ]);
    Route::put('employees/{id}/toggle', [EmployeeController::class, 'toggleStatus'])
        ->name('employees.toggle');
    Route::get('employees/employee/search', [EmployeeController::class, 'search'])
        ->name('employees.search');
    Route::get('/employees/{id}/download-pdf', [EmployeeController::class, 'exportEmployeeProfileDetailsAsPDF'])
        ->name('employees.download-pdf');

    //------------------------------------------------------------

    Route::resource('stages', StageController::class)->except('show')
        ->names([
            'index'   => 'stages.index',
            'create'  => 'stages.create',
            'store'   => 'stages.store',
            'edit'    => 'stages.edit',
            'update'  => 'stages.update',
            'destroy' => 'stages.destroy',
        ]);
    Route::get('stages/orders/get-queue', [StageController::class, 'getMaxOrder'])
        ->name('stages.getOrder');

    //------------------------------------------------------------

    Route::group([
        'as'        =>'employee-stages.',
        'prefix'    =>'/employee-stages',
    ],function() {
        Route::get('single-employee',[EmployeeStageController::class , 'getSingleEmployee'])
            ->name('getSingleEmployee');

        Route::get('upcoming/employee-steps',[EmployeeStageController::class , 'getPending'])
            ->name('getPendingJobs');

        Route::get('pay-stage/{id}', [EmployeeStageController::class , 'showPayEmployeeStagePage'])->name('get-pay-page');
        Route::post('pay-employee-stage', [EmployeeStageController::class , 'PayEmployeeStage'])->name('pay');
    });

    //------------------------------------------------------------

    Route::resource('payment-accounts',PaymentAccountController::class)
        ->middleware('role-or-permission:super-admin,paymentAccounts')
        ->names([
            'index'     =>'paymentAccounts.index',
            'create'    =>'paymentAccounts.create',
            'store'     =>'paymentAccounts.store',
            'show'      =>'paymentAccounts.show',
            'edit'      =>'paymentAccounts.edit',
            'update'    =>'paymentAccounts.update',
            'destroy'   =>'paymentAccounts.destroy',
        ]);

    Route::post('payment-accounts/{id}/charge', [PaymentAccountController::class, 'charge'])
        ->middleware('role-or-permission:super-admin,chargePaymentAccounts')
        ->name('paymentAccounts.charge');
    //------------------------------------------------------------

    Route::resource('admins',AdminController::class)->except('show')
        ->names([
            'index'    =>'admins.index',
            'create'   =>'admins.create',
            'store'    =>'admins.store',
            'edit'     =>'admins.edit',
            'update'   =>'admins.update',
            'destroy'  =>'admins.destroy',
        ]);
    //------------------------------------------------------------

    Route::resource('company-moderators',CompanyModeratorsController::class)
        ->names([
            'index'      =>'moderators.index',
            'create'     =>'moderators.create',
            'store'      =>'moderators.store',
            'edit'       =>'moderators.edit',
            'update'     =>'moderators.update',
            'destroy'    =>'moderators.destroy',
        ]);

    //------------------------------------------------------------

    //---------------------------------------------------
Route::group(['prefix' => 'hr'], function () {

    Route::resource('leaves', LeaveController::class)
        // ->middleware('role-or-permission:super-admin,leaves')
        ->names([
            'index'     => 'hr.leaves.index',
            'create'    => 'hr.leaves.create',
            'store'     => 'hr.leaves.store',
            'show'      => 'hr.leaves.show',
            'edit'      => 'hr.leaves.edit',
            'update'    => 'hr.leaves.update',
            'destroy'   => 'hr.leaves.destroy',
        ]);


    Route::resource('eos', EndOfServiceController::class)
        // ->middleware('role-or-permission:super-admin,leaves')
        ->names([
            'index'     => 'eos.index',
            'create'    => 'eos.create',
            'store'     => 'eos.store',
            'show'      => 'eos.show',
            'edit'      => 'eos.edit',
            'update'    => 'eos.update',
            'destroy'   => 'eos.destroy'
        ]);
    Route::post('/eos/calculate', [EndOfServiceController::class, 'calculate'])->name('eos.calculate');

    // Reports
    Route::group([
        'controller' => ReportController::class,
        'prefix'     => 'reports',
        'as'         => 'reports.',
    ], function () {
        Route::get('eos', 'EOSReport')->name('eos.report');
        Route::get('leaves', 'LeavesReport')->name('leaves.report');
        Route::get('employees', 'EmployeesReport')->name('employees.report');
        Route::get('transactions', 'TransactionsReport')->name('transactions.report');
        Route::get('employee-details', 'getEmployeeDetails')->name('employee.details');
        Route::get('profit', 'getProfitReport')->name('profit.report');
        Route::get('wallet-transactions', 'getWalletTransactionReport')->name('wallet-transactions.report');
    });

    Route::resource('leave-types', LeaveTypesController::class)
        // ->middleware('role-or-permission:super-admin,leaves')
        ->names([
            'index'    => 'hr.leaveType.index',
            'create'   => 'hr.leaveType.create',
            'store'    => 'hr.leaveType.store',
            'show'     => 'hr.leaveType.show',
            'edit'     => 'hr.leaveType.edit',
            'update'   => 'hr.leaveType.update',
            'destroy'  => 'hr.leaveType.destroy',
        ]);

//    Route::resource('salaries', SalariesController::class)
////         ->middleware('role-or-permission:super-admin,leaves')
//        ->names([
//            'index'                             => 'hr.salaries.index',
//            'create'                            => 'hr.salaries.create',
//            'store'                             => 'hr.salaries.store',
//            'bulkPay'                           => 'hr.salaries.bulk-pay',
//            'generate'                          => 'hr.salaries.bulk-pay',
//            'edit'                              => 'hr.salaries.edit',
//            'update'                            => 'hr.salaries.update',
//            'destroy'                           => 'hr.salaries.destroy',
//        ]);

    Route::get('/salaries', [SalariesController::class, 'index'])->name('hr.salaries.index');
    Route::get('/salaries/create', [SalariesController::class, 'create'])->name('hr.salaries.create');
    Route::post('/salaries/generate', [SalariesController::class, 'generate'])->name('hr.salaries.generate');
    Route::post('/salaries/pay', [SalariesController::class, 'paySalary'])->name('hr.salaries.pay');
    Route::post('/salaries/bulk-pay', [SalariesController::class, 'bulkPaySalaries'])->name('hr.salaries.bulk-pay');
    Route::get('/salaries/company-balance', [SalariesController::class, 'getCompanyBalance'])->name('hr.salaries.company-balance');


    Route::get('/employees/{employee}/files', [EmployeeController::class, 'showFiles'])->name('employees.files');
    Route::get('/employees/{employee}/files/{file}/download', [EmployeeController::class, 'downloadFile'])->name('employees.files.download');
    Route::delete('/employees/files/{file}', [EmployeeController::class, 'destroyFile'])->name('employees.files.delete');
    Route::post('/{employee}/files/upload', [EmployeeController::class, 'uploadFile'])->name('employees.files.upload');

});


    //----------------------------------------------------------------

    Route::resource('users', UserController::class)->except(['show', 'create', 'edit', 'update'])
        ->middleware('role-or-permission:super-admin,users')
        ->names([
            'index'     => 'users.index',
            'destroy'   => 'users.destroy',
        ]);

    Route::put('users/{id}/toggle', [UserController::class, 'toggleStatus'])
        ->middleware('role-or-permission:super-admin,users')
        ->name('users.toggle');
    Route::post('users/export', [UserController::class, 'export'])
        ->middleware('role-or-permission:super-admin,users')
        ->name('users.export');

    //-------------------------------------------------------------------

    Route::get('notify-users', [SendPushNotification::class, 'notifyUsers'])->name('notifications.user');
    Route::post('notify-users', [SendPushNotification::class, 'sendUsersNotification'])->name('notifications.user');
    Route::get('notify-cashiers', [SendPushNotification::class, 'notifyCashiers'])->name('notifications.cashier');
    Route::post('notify-cashiers', [SendPushNotification::class, 'sendCashierNotification'])->name('notifications.cashier');

    //--------------------------------------------------------------------
});
