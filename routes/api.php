<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonController;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
|
| Base URL: /api/v1
| Authentication: JWT via 'auth:api' middleware
|
| Route Structure:
| - Public routes (login, signup, visitor logging)
| - Common protected routes (me, logout, notifications)
| - Role-based routes (included from separate files)
|
*/

// PUBLIC ROUTES (No Authentication Required)
Route::prefix( 'v1' )->group( function () {

    Route::controller( CommonController::class )->group( function () {
        // Authentication
        Route::post( 'Login', 'login' )->name( 'api.login' );

        // Registration
        Route::post( 'SignUpFreelancer', 'signUpFreelancer' )->name( 'api.signup.freelancer' );
        Route::post( 'SignUpFreelancerDetails', 'signUpFreelancerDetails' )->name( 'api.signup.freelancer.details' );
        Route::post( 'SignUpCompanyDetails', 'signUpCompanyDetails' )->name( 'api.signup.company.details' );

        // File Upload
        Route::post( 'UploadImage', 'uploadFile' )->name( 'api.upload.file' );

        // OAuth Callbacks
        Route::get( 'auth/linkedin/callback/signup', 'linkedInCallback' )->name( 'api.oauth.linkedin.callback' );

    } );

    // Visitor Logging (Public)
    Route::post( 'LogVisitor', [ CommonController::class, 'logVisitor' ] )->name( 'api.visitor.log' );
} );


// PROTECTED ROUTES (Authentication Required)
Route::prefix( 'v1' )->middleware( [ 'auth:api' ] )->group( function () {

    // COMMON ROUTES (All Authenticated Users)
    Route::controller( CommonController::class )->group( function () {
        // User Profile & Auth
        Route::get( 'me', 'me' )->name( 'api.user.profile' );
        Route::post( 'logout', 'logout' )->name( 'api.logout' );

        // Notifications
        Route::get( 'Notifications', 'notifications' )->name( 'api.notifications.index' );
        Route::post( 'UpdateNotification', 'updateNotification' )->name( 'api.notifications.update' );

        // Dropdown Data
        Route::get( 'DropdownDataByCategory', 'dropdownDataByCategory' )->name( 'api.dropdown.category' );
    } );

    // ROLE-BASED ROUTES (Included from separate files)

    /*
     * Include Freelancer Routes
     * File: routes/api/freelancer_routes.php
     * Prefix: /api/v1/freelancer
     */
    require __DIR__ . '/api/freelancer_routes.php';

    /*
     * Include Company Routes
     * File: routes/api/company_routes.php
     * Prefix: /api/v1/company
     */
    require __DIR__ . '/api/company_routes.php';

    /*
     * Include Admin Routes
     * File: routes/api/admin_routes.php
     * Prefix: /api/v1/admin
     */
    require __DIR__ . '/api/admin_routes.php';
} );

// FALLBACK ROUTE (404 Handler)
Route::fallback( function () {
    return response()->json( [
        'StatusCode' => 404,
        'Message'    => 'API endpoint not found. Please check the URL and try again.',
        'Success'    => false,
        'Timestamp'  => now()->toIso8601String(),
    ], 404 );
} );
