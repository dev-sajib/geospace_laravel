<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Freelancer\HomeController as FreelancerHomeController;
use App\Http\Controllers\Freelancer\FreelancerTimesheetController;

/*
|--------------------------------------------------------------------------
| Freelancer Routes
|--------------------------------------------------------------------------
|
| All routes for Freelancer role users
| Prefix: /api/v1/freelancer
| Middleware: auth:api (or auth:sanctum based on your auth system)
|
*/

Route::prefix('freelancer')->middleware(['auth:api'])->name('api.freelancer.')->group(function () {

    // DASHBOARD & PROFILE
    Route::controller(FreelancerHomeController::class)->group(function () {
        Route::get('UserList', 'userList')->name('users.list');
        // Add more dashboard/profile routes here as needed
    });

    // TIMESHEET MANAGEMENT
    Route::controller(FreelancerTimesheetController::class)->prefix('timesheets')->name('timesheets.')->group(function () {
        // List & Dropdown
        Route::get('/', 'index')->name('index');
        Route::get('dropdown-data', 'getDropdownData')->name('dropdown');
        Route::get('get-projects', 'getProjects')->name('get.projects');

        // CRUD Operations
        Route::post('/', 'store')->name('store');
        Route::get('{id}', 'show')->name('show');

        // Timesheet Actions
        Route::put('{id}/resubmit', 'resubmit')->name('resubmit');
        Route::post('{id}/request-payment', 'requestPayment')->name('request.payment');

        // Payment History
        Route::get('payment-history', 'paymentHistory')->name('payment.history');
    });
});
