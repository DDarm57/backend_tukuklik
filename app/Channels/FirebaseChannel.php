<?php

namespace App\Channels;

use App\Http\Controllers\Notification\FirebaseController;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class FirebaseChannel
{
    public function send($notifiable, Notification $notification) {
        
        $id = $notifiable->getKey();

        $data = method_exists($notification, 'toFcm')
            ? $notification->toFcm($notifiable)
            : $notification->toArray($notifiable);
        if (empty($data)) {
            return;
        }
        
        $data['user_id'] = $id;
        Log::info($data);
        $this->sendFCM($data);

        return true;
    }

    private function sendFCM($data)
    {
        $firebase = App::make(FirebaseController::class);
        $firebase->sendMessage($data);
        return true;
    }
}