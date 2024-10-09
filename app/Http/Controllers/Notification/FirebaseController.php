<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FirebaseController extends Controller
{
    public $serverUrl;
    public $serverKey;

    public function __construct()
    {
        $this->serverUrl = config('services.firebase.url');
        $this->serverKey = config('services.firebase.server_key'); 
    }

    public function store(Request $request)
    {
        User::where('id', Auth::user()->id ?? 0)->update([
            'fcm_token' => $request->token
        ]);
        return response()->json(['status' => 'success']);
    }

    public function sendMessage($payload)
    {
        $fcm = User::query()
            ->where('id', $payload['user_id'])
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->all();
        
        $user = UserRepository::findById($payload['user_id']);

        $client = new Client();

        $request = $client->request('POST', $this->serverUrl,[
            'headers' => [
                'Authorization' => 'key='.$this->serverKey
            ],
            'json'  => [
                "registration_ids" => $fcm,
                "notification" => [
                    "title" => $payload['title'],
                    "body" => $payload['message'],
                    "icon" => Storage::url('settings/logo.png'),
                    'click_action' => $payload['url'],
                    "priority" => "high",
                ]
            ]
        ]);
        $response = $request->getBody();
        Log::info($response);
    }
}
