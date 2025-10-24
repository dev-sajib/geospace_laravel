<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use Carbon\Carbon;

class CleanupOldChatMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of days to keep messages.
     */
    protected $daysToKeep;

    /**
     * Create a new job instance.
     */
    public function __construct($daysToKeep = 30)
    {
        $this->daysToKeep = $daysToKeep;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $cutoffDate = Carbon::now()->subDays($this->daysToKeep);

        // Get old messages with their attachments
        $oldMessages = Message::where('created_at', '<', $cutoffDate)
            ->whereNotNull('attachment_path')
            ->get(['id', 'attachment_path']);

        // Delete attachment files
        foreach ($oldMessages as $message) {
            if ($message->attachment_path) {
                Storage::disk('public')->delete($message->attachment_path);
            }
        }

        // Delete old messages
        $deletedMessages = Message::where('created_at', '<', $cutoffDate)->delete();

        // Get conversations that have no messages left
        $emptyConversations = Conversation::whereDoesntHave('messages')->get();

        // Delete attachment files for empty conversations
        foreach ($emptyConversations as $conversation) {
            if ($conversation->attachment_path) {
                Storage::disk('public')->delete($conversation->attachment_path);
            }
        }

        // Delete empty conversations and their participants
        foreach ($emptyConversations as $conversation) {
            // Delete participants first
            ConversationParticipant::where('conversation_id', $conversation->id)->delete();
            
            // Delete the conversation
            $conversation->delete();
        }

        // Log the cleanup results
        \Log::info('Chat cleanup completed', [
            'deleted_messages' => $deletedMessages,
            'deleted_conversations' => $emptyConversations->count(),
            'cutoff_date' => $cutoffDate->toDateString(),
            'days_kept' => $this->daysToKeep
        ]);
    }
}