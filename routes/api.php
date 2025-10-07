<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Company\HomeController as CompanyHomeController;
use App\Http\Controllers\Freelancer\HomeController as FreelancerHomeController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ContractManagementController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (no authentication required)
Route::group( [ 'prefix' => 'v1' ], function () {
    // Authentication routes
    Route::post( 'Login', [ CommonController::class, 'login' ] );
    Route::post( 'SignUpFreelancer', [ CommonController::class, 'signUpFreelancer' ] );
    Route::post( 'SignUpFreelancerDetails', [ CommonController::class, 'signUpFreelancerDetails' ] );
    Route::post( 'SignUpCompanyDetails', [ CommonController::class, 'signUpCompanyDetails' ] );

    // Visitor logging (public access for tracking)
    Route::post( 'LogVisitor', [ CommonController::class, 'logVisitor' ] );

    // LinkedIn OAuth callback
    Route::get( 'api/auth/linkedin/callback/signup', [ CommonController::class, 'linkedInCallback' ] );
} );

// Protected routes (authentication required)
Route::group( [ 'prefix' => 'v1', 'middleware' => [ 'auth:api' ] ], function () {

    // Common routes
    Route::get( 'GetMenusByRoleId', [ CommonController::class, 'getMenusByRoleId' ] );
    Route::get( 'Notifications', [ CommonController::class, 'notifications' ] );
    Route::post( 'UpdateNotification', [ CommonController::class, 'updateNotification' ] );
    Route::get( 'DropdownDataByCategory', [ CommonController::class, 'dropdownDataByCategory' ] );
    Route::post( 'logout', [ CommonController::class, 'logout' ] );
    Route::get( 'me', [ CommonController::class, 'me' ] );

    // Company routes
    Route::group( [ 'prefix' => 'company' ], function () {
        Route::get( 'CurrentProjectList', [ CompanyHomeController::class, 'currentProjectList' ] );
        Route::get( 'ActiveFreelancerList', [ CompanyHomeController::class, 'activeFreelancerList' ] );
        Route::get( 'CompanyPendingTimesheetList', [ CompanyHomeController::class, 'companyPendingTimesheetList' ] );
        Route::get( 'NotificationList', [ CompanyHomeController::class, 'notificationList' ] );
        Route::get( 'DashboardStats', [ CompanyHomeController::class, 'dashboardStats' ] );
        Route::get( 'UpdateProfileList', [ CompanyHomeController::class, 'updateProfileList' ] );
        Route::post( 'CreateProfileServices', [ CompanyHomeController::class, 'createProfileServices' ] );
    } );

    // Freelancer routes
    Route::group( [ 'prefix' => 'freelancer' ], function () {
        Route::get( 'UserList', [ FreelancerHomeController::class, 'userList' ] );
    } );

    // Admin routes
    Route::group( [ 'prefix' => 'admin' ], function () {
        Route::get( 'VerifiedUserList', [ UserManagementController::class, 'verifiedUserList' ] );
        Route::get( 'PendingVerificationList', [ UserManagementController::class, 'pendingVerificationList' ] );
        Route::get( 'SuspendedAccountsList', [ UserManagementController::class, 'suspendedAccountsList' ] );
        Route::post( 'UpdateUserStatus', [ UserManagementController::class, 'updateUserStatus' ] );
        Route::post( 'VerifyUser', [ UserManagementController::class, 'verifyUser' ] );
        Route::get( 'GetUserDetails', [ UserManagementController::class, 'getUserDetails' ] );

        //CONTRACTS API
        Route::get( 'contracts/stats', [ ContractManagementController::class, 'statistics' ] );
        Route::get( 'contracts', [ ContractManagementController::class, 'index' ] );
        Route::post( 'contracts', [ ContractManagementController::class, 'store' ] );
        Route::get( 'contracts/{id}', [ ContractManagementController::class, 'show' ] );
        Route::put( 'contracts/{id}', [ ContractManagementController::class, 'update' ] );
        Route::patch( 'contracts/{id}', [ ContractManagementController::class, 'update' ] );
        Route::delete( 'contracts/{id}', [ ContractManagementController::class, 'destroy' ] );
        // Timesheet Statistics
        Route::get( 'timesheets/stats', [
            App\Http\Controllers\Admin\TimesheetManagementController::class,
            'statistics'
        ] );

        // Pending Timesheets
        Route::get( 'timesheets/pending', [
            App\Http\Controllers\Admin\TimesheetManagementController::class,
            'pendingTimesheets'
        ] );

        // Approved Timesheets - ADD THIS LINE
        Route::get( 'timesheets/approved', [
            App\Http\Controllers\Admin\TimesheetManagementController::class,
            'approvedTimesheets'
        ] );
        // Timesheet CRUD Operations
        Route::get( 'timesheets', [ App\Http\Controllers\Admin\TimesheetManagementController::class, 'index' ] );
        Route::post( 'timesheets', [ App\Http\Controllers\Admin\TimesheetManagementController::class, 'store' ] );
        Route::get( 'timesheets/{id}', [ App\Http\Controllers\Admin\TimesheetManagementController::class, 'show' ] );
        Route::put( 'timesheets/{id}', [ App\Http\Controllers\Admin\TimesheetManagementController::class, 'update' ] );
        Route::patch( 'timesheets/{id}', [
            App\Http\Controllers\Admin\TimesheetManagementController::class,
            'update'
        ] );
        Route::delete( 'timesheets/{id}', [
            App\Http\Controllers\Admin\TimesheetManagementController::class,
            'destroy'
        ] );

        // Timesheet Approval Actions
        Route::post( 'timesheets/{id}/approve', [
            App\Http\Controllers\Admin\TimesheetManagementController::class,
            'approve'
        ] );
        Route::post( 'timesheets/{id}/reject', [
            App\Http\Controllers\Admin\TimesheetManagementController::class,
            'reject'
        ] );

    } );
} );

// Fallback route for API
Route::fallback( function () {
    return response()->json( [
        'StatusCode' => 404,
        'Message'    => 'API endpoint not found',
        'Success'    => false
    ], 404 );
} );
