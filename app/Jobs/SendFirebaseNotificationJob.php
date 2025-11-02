<?php

namespace App\Jobs;

use App\Services\FirebasePushNotifications;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFirebaseNotificationJob implements ShouldQueue
{
    use Queueable,InteractsWithQueue , SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $title , public string $description , public string $token)
    {
        $this->title = $title ;
        $this->description = $description ;
        $this->token = $token ;
    }

    /**
     * Execute the job.
     */
    public function handle(FirebasePushNotifications $notification): void
    {
        $notification->sendPushNotification($this->token , $this->title , $this->description);
    }
}
