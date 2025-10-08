<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Freelancer\FreelancerTimesheetController;
use App\Http\Controllers\Company\CompanyTimesheetController;
use App\Http\Controllers\Admin\TimesheetManagementController;

/*
|--------------------------------------------------------------------------
| Timesheet API Routes
|--------------------------------------------------------------------------
|
| Complete timesheet workflow routes for Freelancer, Company, and Admin
|
*/

// ============================================
// FREELANCER ROUTES
// ============================================
Route::prefix('v1/freelancer')->middleware(['auth:sanctum', 'role:Freelancer'])->group(function () {
    
    // Timesheet CRUD
    Route::get('timesheets', [FreelancerTimesheetController::class, 'index']);
    Route::get('timesheets/dropdown-data', [FreelancerTimesheetController::class, 'getDropdownData']);
    Route::post('timesheets', [FreelancerTimesheetController::class, 'store']);
    Route::get('timesheets/{id}', [FreelancerTimesheetController::class, 'show']);
    
    // Timesheet Actions
    Route::put('timesheets/{id}/resubmit', [FreelancerTimesheetController::class, 'resubmit']);
    Route::post('timesheets/{id}/request-payment', [FreelancerTimesheetController::class, 'requestPayment']);
    
    // Payment History
    Route::get('timesheets/payment-history', [FreelancerTimesheetController::class, 'paymentHistory']);
});

// ============================================
// COMPANY ROUTES
// ============================================
Route::prefix('v1/company')->middleware(['auth:sanctum', 'role:Company'])->group(function () {
    
    // Timesheet Management
    Route::get('timesheets', [CompanyTimesheetController::class, 'index']);
    Route::get('timesheets/pending', [CompanyTimesheetController::class, 'pendingTimesheets']);
    Route::get('timesheets/accepted', [CompanyTimesheetController::class, 'acceptedTimesheets']);
    Route::get('timesheets/{id}', [CompanyTimesheetController::class, 'show']);
    
    // Timesheet Review Actions
    Route::post('timesheets/{id}/days/{dayId}/comment', [CompanyTimesheetController::class, 'addDayComment']);
    Route::post('timesheets/{id}/accept', [CompanyTimesheetController::class, 'accept']);
    Route::post('timesheets/{id}/reject', [CompanyTimesheetController::class, 'reject']);
    
    // Invoice and Payment Management
    Route::post('invoices/{invoiceId}/complete-payment', [CompanyTimesheetController::class, 'completePayment']);
    Route::get('payments/history', [CompanyTimesheetController::class, 'paymentHistory']);
});

// ============================================
// ADMIN ROUTES
// ============================================
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'role:Admin'])->group(function () {
    
    // Timesheet Management
    Route::get('timesheets', [TimesheetManagementController::class, 'index']);
    Route::get('timesheets/accepted', [TimesheetManagementController::class, 'acceptedTimesheets']);
    Route::get('timesheets/{id}', [TimesheetManagementController::class, 'show']);
    Route::delete('timesheets/{id}', [TimesheetManagementController::class, 'destroy']);
    
    // Payment Management
    Route::get('payment-requests', [TimesheetManagementController::class, 'paymentRequests']);
    Route::post('payment-requests/{requestId}/process', [TimesheetManagementController::class, 'processFreelancerPayment']);
    
    // Company Payment Verification
    Route::get('payments/company-payments', [TimesheetManagementController::class, 'companyPayments']);
    Route::post('payments/{paymentId}/verify', [TimesheetManagementController::class, 'verifyCompanyPayment']);
    
    // Invoice Management
    Route::get('invoices/{invoiceId}/download', [TimesheetManagementController::class, 'downloadInvoice']);
    
    // Statistics
    Route::get('timesheets/statistics', [TimesheetManagementController::class, 'statistics']);
});

/*
|--------------------------------------------------------------------------
| API Endpoint Summary
|--------------------------------------------------------------------------
|
| FREELANCER ENDPOINTS:
| - GET    /api/v1/freelancer/timesheets                           - List all timesheets
| - GET    /api/v1/freelancer/timesheets/dropdown-data            - Get dropdown data for creating timesheet
| - POST   /api/v1/freelancer/timesheets                          - Create and submit new timesheet
| - GET    /api/v1/freelancer/timesheets/{id}                     - Get specific timesheet details
| - PUT    /api/v1/freelancer/timesheets/{id}/resubmit            - Resubmit rejected timesheet
| - POST   /api/v1/freelancer/timesheets/{id}/request-payment     - Request payment for accepted timesheet
| - GET    /api/v1/freelancer/timesheets/payment-history          - Get payment history
|
| COMPANY ENDPOINTS:
| - GET    /api/v1/company/timesheets                             - List all company timesheets
| - GET    /api/v1/company/timesheets/pending                     - List pending timesheets
| - GET    /api/v1/company/timesheets/accepted                    - List accepted timesheets
| - GET    /api/v1/company/timesheets/{id}                        - Get specific timesheet details
| - POST   /api/v1/company/timesheets/{id}/days/{dayId}/comment   - Add comment to specific day
| - POST   /api/v1/company/timesheets/{id}/accept                 - Accept timesheet
| - POST   /api/v1/company/timesheets/{id}/reject                 - Reject timesheet
| - POST   /api/v1/company/invoices/{invoiceId}/complete-payment  - Mark payment as complete
| - GET    /api/v1/company/payments/history                       - Get payment history
|
| ADMIN ENDPOINTS:
| - GET    /api/v1/admin/timesheets                               - List all timesheets
| - GET    /api/v1/admin/timesheets/accepted                      - List accepted timesheets
| - GET    /api/v1/admin/timesheets/{id}                          - Get specific timesheet details
| - DELETE /api/v1/admin/timesheets/{id}                          - Delete timesheet
| - GET    /api/v1/admin/payment-requests                         - List all payment requests
| - POST   /api/v1/admin/payment-requests/{requestId}/process     - Process freelancer payment
| - GET    /api/v1/admin/payments/company-payments                - List company payments for verification
| - POST   /api/v1/admin/payments/{paymentId}/verify              - Verify company payment
| - GET    /api/v1/admin/invoices/{invoiceId}/download            - Download invoice
| - GET    /api/v1/admin/timesheets/statistics                    - Get timesheet statistics
|
*/
