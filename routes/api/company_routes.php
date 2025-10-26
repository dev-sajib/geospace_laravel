<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\HomeController as CompanyHomeController;
use App\Http\Controllers\Company\CompanyTimesheetController;

/*
|--------------------------------------------------------------------------
| Company Routes
|--------------------------------------------------------------------------
|
| All routes for Company role users
| Prefix: /api/v1/company
| Middleware: auth:api (or auth:sanctum based on your auth system)
|
*/

Route::prefix('company')->middleware(['auth:api'])->name('api.company.')->group(function () {

    // DASHBOARD & PROFILE
    Route::controller(CompanyHomeController::class)->group(function () {
        Route::get('DashboardStats', 'dashboardStats')->name('dashboard.stats');
        Route::get('CurrentProjectList', 'currentProjectList')->name('projects.current');
        Route::get('ActiveFreelancerList', 'activeFreelancerList')->name('freelancers.active');
        Route::get('CompanyPendingTimesheetList', 'companyPendingTimesheetList')->name('timesheets.pending.list');
        Route::get('NotificationList', 'notificationList')->name('notifications.list');
        Route::get('UpdateProfileList', 'updateProfileList')->name('profile.list');
        Route::post('CreateProfileServices', 'createProfileServices')->name('profile.services.create');
        Route::get('dashboard/stats', 'getDashboardStats')->name('dashboard.stats');

        // Company Profile Management
        Route::get('GetCompanyProfile', 'getCompanyProfile')->name('profile.get');
        Route::post('UpdateCompanyProfile', 'updateCompanyProfile')->name('profile.update');
    });

    // TIMESHEET MANAGEMENT
    Route::controller(CompanyTimesheetController::class)->prefix('timesheets')->name('timesheets.')->group(function () {
        // List Timesheets
        Route::get('/', 'index')->name('index');
        Route::get('pending', 'pendingTimesheets')->name('pending');
        Route::get('accepted', 'acceptedTimesheets')->name('accepted');

        // View Timesheet
        Route::get('{id}', 'show')->name('show');

        // Review Actions
        Route::post('{id}/days/{dayId}/comment', 'addDayComment')->name('day.comment');
        Route::post('{id}/accept', 'accept')->name('accept');
        Route::post('{id}/reject', 'reject')->name('reject');
    });

    // INVOICE & PAYMENT MANAGEMENT
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::post('{invoiceId}/complete-payment', [CompanyTimesheetController::class, 'completePayment'])->name('complete.payment');
    });

    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('history', [CompanyTimesheetController::class, 'paymentHistory'])->name('history');
    });
});
