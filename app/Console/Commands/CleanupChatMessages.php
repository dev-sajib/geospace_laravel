<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CleanupOldChatMessages;

class CleanupChatMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:cleanup {--days=30 : Number of days to keep messages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old chat messages and conversations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        
        $this->info("Starting chat cleanup for messages older than {$days} days...");
        
        // Dispatch the cleanup job
        CleanupOldChatMessages::dispatch($days);
        
        $this->info('Chat cleanup job has been dispatched successfully.');
        
        return 0;
    }
}