<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register the routes needed for broadcasting auth (this creates the /broadcasting/auth route)
        Broadcast::routes(['middleware' => ['auth:sanctum']]);
        Log::info('Broadcast auth user: ', ['user' => Auth::user()]);

        // Define your broadcast channel authorizations here
        Broadcast::channel('chat.{userId}', function ($user, $userId) {
            return (int) $user->id === (int) $userId;
        });
    }
}
