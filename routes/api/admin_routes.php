<?php

use App\Http\Controllers\Admin\DashboardStatsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ContractManagementController;
use App\Http\Controllers\Admin\AdminTimesheetManagementController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| All routes for Admin role users
| Prefix: /api/v1/admin
| Middleware: auth:api (or auth:sanctum based on your auth system)
|
*/

Route::prefix('admin')->middleware(['auth:api'])->name('api.admin.')->group(function () {
    Route::get('/dashboard/stats', [DashboardStatsController::class, 'getDashboardStats']);

    // USER MANAGEMENT
    Route::controller(UserManagementController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('verified', 'verifiedUserList')->name('verified');
        Route::get('pending-verification', 'pendingVerificationList')->name('pending');
        Route::get('suspended', 'suspendedAccountsList')->name('suspended');
        Route::get('details', 'getUserDetails')->name('details');
        Route::post('send-verification-request', 'sendVerificationRequest')->name('send.verification');
        Route::post('final-verification', 'finalVerification')->name('final.verification');
        Route::post('update-status', 'updateUserStatus')->name('update.status');
    });

    // CONTRACT MANAGEMENT
    Route::controller(ContractManagementController::class)->prefix('contracts')->name('contracts.')->group(function () {
        Route::get('stats', 'statistics')->name('stats');
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('{id}', 'show')->name('show');
        Route::match(['put', 'patch'], '{id}', 'update')->name('update');
        Route::delete('{id}', 'destroy')->name('destroy');
    });

    // TIMESHEET MANAGEMENT
    Route::controller(AdminTimesheetManagementController::class)->prefix('timesheets')->name('timesheets.')->group(function () {
        // Statistics & Lists
        Route::get('stats', 'statistics')->name('stats');
        Route::get('statistics', 'statistics')->name('statistics'); // Alias for stats
        Route::get('pending', 'pendingTimesheets')->name('pending');
        Route::get('approved', 'approvedTimesheets')->name('approved');
        Route::get('accepted', 'acceptedTimesheets')->name('accepted');

        // CRUD Operations
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('{id}', 'show')->name('show');
        Route::get('{id}/details', 'getTimesheetDetails')->name('details');
        Route::match(['put', 'patch'], '{id}', 'update')->name('update');
        Route::delete('{id}', 'destroy')->name('destroy');

        // Approval Actions
        Route::post('{id}/approve', 'approve')->name('approve');
        Route::post('{id}/reject', 'reject')->name('reject');
    });

    // PAYMENT REQUEST MANAGEMENT
    Route::prefix('payment-requests')->name('payment.requests.')->group(function () {
        Route::get('/', [AdminTimesheetManagementController::class, 'paymentRequests'])->name('index');
        Route::post('{requestId}/process', [AdminTimesheetManagementController::class, 'processFreelancerPayment'])->name('process');
    });

    // PAYMENT VERIFICATION
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('company-payments', [AdminTimesheetManagementController::class, 'companyPayments'])->name('company');
        Route::post('{paymentId}/verify', [AdminTimesheetManagementController::class, 'verifyCompanyPayment'])->name('verify');
    });

    // INVOICE MANAGEMENT
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('{invoiceId}/download', [AdminTimesheetManagementController::class, 'downloadInvoice'])->name('download');
    });
});
