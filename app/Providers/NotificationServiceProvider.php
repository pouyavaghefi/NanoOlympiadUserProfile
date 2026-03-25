<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification\UserNotif;

class NotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::share('notifications', UserNotif::latest()->take(10)->get());
    }

    public function register()
    {
        //
    }
}