<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SaveNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $eventId;
    public $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($eventId, $message)
    {
        $this->eventId = $eventId;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $id = Str::random(30);
        try {
            // Save the notification to the database
            DB::table('admin_notifications')->insert([
                'id' => $this->id,
                'message' => $this->message,
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving notification: ' . $e->getMessage());
        }
    }
}
