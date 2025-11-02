<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebasePushNotifications 
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(
            base_path(config('services.firebase.credentials'))
        );

        $this->messaging = $factory->createMessaging();
    }

    public function sendPushNotification($fcm_token , $title , $body , $data = [])
    {
        $notification = Notification::create($title , $body);

        $message = CloudMessage::new()
        ->withNotification($notification)
        ->withData($data)
        ->toToken($fcm_token);

        return $this->messaging->send($message);
    }
}