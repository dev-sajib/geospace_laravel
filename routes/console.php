<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\CleanupOldChatMessages;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule chat cleanup to run daily at 2 AM
Schedule::job(new CleanupOldChatMessages(30))->dailyAt('02:00');
