<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\HomeController as CompanyHomeController;
use App\Http\Controllers\Company\CompanyTimesheetController;
use App\Http\Controllers\Company\CompanyVideoSupportController;
use App\Http\Controllers\Company\CompanyInvoiceController;

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
        Route::get('dashboard/stats', 'getDashboardStats')->name('stats');
        Route::get('CurrentProjectList', 'currentProjectList')->name('projects.current');
        Route::get('ActiveFreelancerList', 'activeFreelancerList')->name('freelancers.active');
        Route::get('CompanyPendingTimesheetList', 'companyPendingTimesheetList')->name('timesheets.pending.list');
        Route::get('NotificationList', 'notificationList')->name('notifications.list');
        Route::get('UpdateProfileList', 'updateProfileList')->name('profile.list');
        Route::post('CreateProfileServices', 'createProfileServices')->name('profile.services.create');

        // Company Profile Management
        Route::get('GetCompanyProfile', 'getCompanyProfile')->name('profile.get');
        Route::post('UpdateCompanyProfile', 'updateCompanyProfile')->name('profile.update');
        
        // Project Management
        Route::post('CreateProject', 'createProject')->name('project.create');
        Route::get('GetProjects', 'getProjects')->name('project.list');

        // Feedback Management
        Route::get('GetCompletedProjects', 'getCompletedProjects')->name('feedback.completed.projects');
        Route::post('SubmitFeedback', 'submitFeedback')->name('feedback.submit');
        Route::get('GetFeedbackList', 'getFeedbackList')->name('feedback.list');

        // Freelancer Profiles
        Route::get('GetFreelancerProfiles', 'getFreelancerProfiles')->name('freelancer.profiles');

        // Contract Management
        Route::post('UpdateContractStatus', 'updateContractStatus')->name('contract.status.update');
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
    Route::controller(CompanyInvoiceController::class)->prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', 'getInvoices')->name('list');
        Route::get('{invoice_id}/details', 'getInvoiceDetails')->name('details');
        Route::post('{invoice_id}/update-status', 'updateInvoiceStatus')->name('update.status');
        Route::post('{invoiceId}/complete-payment', [CompanyTimesheetController::class, 'completePayment'])->name('complete.payment');
    });

    // UPCOMING PAYMENTS MANAGEMENT
    Route::controller(CompanyInvoiceController::class)->prefix('upcoming-payments')->name('upcoming-payments.')->group(function () {
        Route::get('/', 'getUpcomingPayments')->name('list');
        Route::post('/process-payment', 'processPayment')->name('process');
    });

    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('history', [CompanyTimesheetController::class, 'paymentHistory'])->name('history');
    });

    // VIDEO SUPPORT
    Route::controller(CompanyVideoSupportController::class)->prefix('video-support')->name('video-support.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('{requestId}', 'show')->name('show');
    });

    // CHAT SUPPORT
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::post('conversations', [App\Http\Controllers\Api\ChatController::class, 'createConversation'])->name('conversations.store');
        Route::get('conversations', [App\Http\Controllers\Api\ChatController::class, 'getConversations'])->name('conversations.index');
        Route::delete('conversations/{conversationId}', [App\Http\Controllers\Api\ChatController::class, 'deleteConversation'])->name('conversations.delete');
        Route::get('conversations/{conversationId}/messages', [App\Http\Controllers\Api\ChatController::class, 'getMessages'])->name('conversations.messages');
        Route::post('conversations/{conversationId}/messages', [App\Http\Controllers\Api\ChatController::class, 'sendMessage'])->name('conversations.messages.store');
        Route::post('conversations/{conversationId}/typing', [App\Http\Controllers\Api\ChatController::class, 'sendTypingIndicator'])->name('conversations.typing');
    });

    // DISPUTE TICKET MANAGEMENT
    Route::prefix('dispute')->name('dispute.')->group(function () {
        Route::get('company-contracts/{userId}', [App\Http\Controllers\DisputeController::class, 'getCompanyContracts'])->name('company.contracts');
        Route::get('tickets/{userId}', [App\Http\Controllers\DisputeController::class, 'getCompanyTickets'])->name('tickets.list');
        Route::post('tickets/submit', [App\Http\Controllers\DisputeController::class, 'submitTicket'])->name('tickets.submit');
    });
});
