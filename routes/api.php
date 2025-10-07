<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Company\HomeController as CompanyHomeController;
use App\Http\Controllers\Freelancer\HomeController as FreelancerHomeController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ContractManagementController;
use App\Http\Controllers\Admin\TimesheetManagementController;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
| All routes are prefixed with /api/v1
| Authentication: JWT via 'auth:api' middleware
*/

// Public Routes
Route::prefix('v1')->group(function () {

    Route::controller(CommonController::class)->group(function () {
        Route::post('Login', 'login')->name('api.login');
        Route::post('SignUpFreelancer', 'signUpFreelancer')->name('api.signup.freelancer');
        Route::post('SignUpFreelancerDetails', 'signUpFreelancerDetails')->name('api.signup.freelancer.details');
        Route::post('SignUpCompanyDetails', 'signUpCompanyDetails')->name('api.signup.company.details');
    });

    Route::post('LogVisitor', [CommonController::class, 'logVisitor'])->name('api.visitor.log');
    Route::get('api/auth/linkedin/callback/signup', [CommonController::class, 'linkedInCallback'])->name('api.oauth.linkedin.callback');
});

// Protected Routes
Route::prefix('v1')->middleware(['auth:api'])->group(function () {

    Route::controller(CommonController::class)->group(function () {
        Route::get('me', 'me')->name('api.user.profile');
        Route::post('logout', 'logout')->name('api.logout');
        Route::get('GetMenusByRoleId', 'getMenusByRoleId')->name('api.menus.role');
        Route::get('Notifications', 'notifications')->name('api.notifications.index');
        Route::post('UpdateNotification', 'updateNotification')->name('api.notifications.update');
        Route::get('DropdownDataByCategory', 'dropdownDataByCategory')->name('api.dropdown.category');
    });

    Route::prefix('company')->name('api.company.')->group(function () {
        Route::controller(CompanyHomeController::class)->group(function () {
            Route::get('DashboardStats', 'dashboardStats')->name('dashboard.stats');
            Route::get('CurrentProjectList', 'currentProjectList')->name('projects.current');
            Route::get('ActiveFreelancerList', 'activeFreelancerList')->name('freelancers.active');
            Route::get('CompanyPendingTimesheetList', 'companyPendingTimesheetList')->name('timesheets.pending');
            Route::get('NotificationList', 'notificationList')->name('notifications.list');
            Route::get('UpdateProfileList', 'updateProfileList')->name('profile.list');
            Route::post('CreateProfileServices', 'createProfileServices')->name('profile.services.create');
        });
    });

    Route::prefix('freelancer')->name('api.freelancer.')->group(function () {
        Route::controller(FreelancerHomeController::class)->group(function () {
            Route::get('UserList', 'userList')->name('users.list');
        });
    });

    Route::prefix('admin')->name('api.admin.')->group(function () {

        Route::controller(UserManagementController::class)->prefix('users')->name('users.')->group(function () {
            Route::get('verified', 'verifiedUserList')->name('verified');
            Route::get('pending-verification', 'pendingVerificationList')->name('pending');
            Route::get('suspended', 'suspendedAccountsList')->name('suspended');
            Route::get('details', 'getUserDetails')->name('details');
            Route::post('update-status', 'updateUserStatus')->name('status.update');
            Route::post('verify', 'verifyUser')->name('verify');
        });

        Route::controller(ContractManagementController::class)->prefix('contracts')->name('contracts.')->group(function () {
            Route::get('stats', 'statistics')->name('stats');
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}', 'show')->name('show');
            Route::match(['put', 'patch'], '/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        Route::controller(TimesheetManagementController::class)->prefix('timesheets')->name('timesheets.')->group(function () {
            Route::get('stats', 'statistics')->name('stats');
            Route::get('pending', 'pendingTimesheets')->name('pending');
            Route::get('approved', 'approvedTimesheets')->name('approved');
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}', 'show')->name('show');
            Route::get('/{id}/details', 'getTimesheetDetails')->name('details');
            Route::match(['put', 'patch'], '/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::post('/{id}/approve', 'approve')->name('approve');
            Route::post('/{id}/reject', 'reject')->name('reject');
        });
    });
});

Route::fallback(function () {
    return response()->json([
        'StatusCode' => 404,
        'Message'    => 'API endpoint not found. Please check the URL and try again.',
        'Success'    => false,
        'Timestamp'  => now()->toIso8601String(),
    ], 404);
});
