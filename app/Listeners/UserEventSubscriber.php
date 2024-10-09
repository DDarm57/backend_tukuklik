<?php
 
namespace App\Listeners;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;
use OwenIt\Auditing\Models\Audit;

class UserEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function handleUserLogin($event) {
        $data = [
            'user_type'         => User::class,
            'auditable_id'      => $event->user->id,
            'auditable_type'    => User::class,
            'event'             => "Logged In",
            'url'               => request()->fullUrl(),
            'ip_address'        => request()->getClientIp(),
            'user_agent'        => request()->userAgent(),
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
            'user_id'           => $event->user->id,
        ];
        Audit::create($data);
    }
 
    /**
     * Handle user logout events.
     */
    public function handleUserLogout($event) {
        $data = [
            'user_type'         => User::class,
            'auditable_id'      => $event->user->id,
            'auditable_type'    => User::class,
            'event'             => "Logged Out",
            'url'               => request()->fullUrl(),
            'ip_address'        => request()->getClientIp(),
            'user_agent'        => request()->userAgent(),
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
            'user_id'           => $event->user->id,
        ];
        Audit::create($data);
    }
 
    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            Login::class,
            [UserEventSubscriber::class, 'handleUserLogin']
        );
 
        $events->listen(
            Logout::class,
            [UserEventSubscriber::class, 'handleUserLogout']
        );
    }
}