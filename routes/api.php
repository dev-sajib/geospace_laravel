<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
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

// HEALTH CHECK ROUTE (For monitoring and deployment verification)
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'GeoSpace API is running',
        'timestamp' => now()->toDateTimeString(),
        'environment' => app()->environment(),
    ]);
});


// DATABASE HEALTH CHECK
Route::get('/health/db', function () {
    try {
        \DB::connection()->getPdo();
        return response()->json([
            'status' => 'ok',
            'message' => 'Database connection successful',
            'database' => \DB::connection()->getDatabaseName(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Database connection failed',
            'error' => $e->getMessage(),
        ], 500);
    }
});

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

        // Public Freelancer Endpoints
        Route::get( 'Freelancers', 'getFreelancers' )->name( 'api.freelancers.list' );
        Route::get( 'Freelancers/{id}', 'getFreelancerById' )->name( 'api.freelancers.show' );

    } );

    // Temporary migration route for Railway deployment
    Route::get('run-migrations', function () {
        try {
            Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();

            return response()->json([
                'status' => 'success',
                'message' => 'Migrations completed successfully',
                'output' => $output
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Migration failed: ' . $e->getMessage()
            ], 500);
        }
    });

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
