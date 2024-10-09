<?php

namespace App\Providers;

use App\Channels\FirebaseChannel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class FirebaseProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Notification::extend('firebase', function($app) {
            return new FirebaseChannel();
        });
    }
}
