<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register the routes needed for broadcasting auth (this creates the /broadcasting/auth route)
        Broadcast::routes(['middleware' => ['auth:sanctum']]);

        // Define your broadcast channel authorizations here
        Broadcast::channel('private-channel.{userId}', function ($user, $userId) {
            return (int) $user->id === (int) $userId;
        });

        // Add more channels as needed...
    }
}
