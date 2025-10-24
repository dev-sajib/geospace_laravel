<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Freelancer\HomeController as FreelancerHomeController;
use App\Http\Controllers\Freelancer\FreelancerTimesheetController;
use App\Http\Controllers\Freelancer\FreelancerInvoiceController;
use App\Http\Controllers\Freelancer\VideoSupportController;
use App\Http\Controllers\Freelancer\ProfileController;
use App\Http\Controllers\DisputeController;

Route::prefix('freelancer')->middleware(['auth:api'])->name('api.freelancer.')->group(function () {

    // DASHBOARD & PROFILE
    Route::controller(FreelancerHomeController::class)->group(function () {
        Route::get('UserList', 'userList')->name('users.list');
        Route::get('dashboard/stats', 'getDashboardStats')->name('dashboard.stats');
        Route::get('contracts/active', 'getActiveContracts')->name('contracts.active');
        Route::get('job-recommendations', 'getJobRecommendations')->name('job.recommendations');
        Route::get('earnings/overview', 'getEarningsOverview')->name('earnings.overview');
    });

    // PROFILE MANAGEMENT
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'getProfile')->name('get');
        Route::put('/', 'updateProfile')->name('update');
        Route::post('/work-experience', 'addWorkExperience')->name('work-experience.add');
        Route::delete('/work-experience/{id}', 'deleteWorkExperience')->name('work-experience.delete');
        Route::post('/education', 'addEducation')->name('education.add');
        Route::delete('/education/{id}', 'deleteEducation')->name('education.delete');
        Route::post('/portfolio', 'addPortfolio')->name('portfolio.add');
        Route::delete('/portfolio/{id}', 'deletePortfolio')->name('portfolio.delete');
        Route::post('/certification', 'addCertification')->name('certification.add');
        Route::delete('/certification/{id}', 'deleteCertification')->name('certification.delete');
        Route::put('/expertise-skills', 'updateExpertiseSkills')->name('expertise-skills.update');
    });

    // TIMESHEET MANAGEMENT
    Route::controller(FreelancerTimesheetController::class)->prefix('timesheets')->name('timesheets.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('dropdown-data', 'getDropdownData')->name('dropdown');
        Route::get('get-projects', 'getProjects')->name('get.projects');
        Route::post('/', 'store')->name('store');
        Route::get('{id}', 'show')->name('show');
        Route::put('{id}/resubmit', 'resubmit')->name('resubmit');
        Route::post('{id}/request-payment', 'requestPayment')->name('request.payment');
        Route::get('payment-history', 'paymentHistory')->name('payment.history');
    });

    // INVOICE MANAGEMENT
    Route::controller(FreelancerInvoiceController::class)->prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', 'getInvoices')->name('list');
    });

    // VIDEO SUPPORT MANAGEMENT
    Route::controller(VideoSupportController::class)->prefix('video-support')->name('video-support.')->group(function () {
        Route::get('/', 'index')->name('list');
        Route::post('/', 'store')->name('create');
        Route::get('{requestId}', 'show')->name('show');
    });

    // DISPUTE TICKET MANAGEMENT
    Route::controller(DisputeController::class)->prefix('dispute')->name('dispute.')->group(function () {
        Route::get('freelancer-contracts/{freelancerId}', 'getFreelancerContracts')->name('freelancer.contracts');
        Route::post('tickets/submit', 'submitTicket')->name('tickets.submit');
    });

    // CHAT SYSTEM
    Route::controller(\App\Http\Controllers\Api\ChatController::class)->prefix('chat')->name('chat.')->group(function () {
        Route::post('conversations', 'createConversation')->name('conversations.create');
        Route::get('conversations', 'getConversations')->name('conversations.list');
        Route::get('conversations/{conversationId}/messages', 'getMessages')->name('messages.list');
        Route::post('conversations/{conversationId}/messages', 'sendMessage')->name('messages.send');
        Route::post('conversations/{conversationId}/typing', 'sendTyping')->name('typing.send');
        Route::delete('conversations/{conversationId}', 'deleteConversation')->name('conversations.delete');
    });
});
