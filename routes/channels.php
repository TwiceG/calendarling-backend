<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('chat.{id}', function ($user, $id) {
    Log::info('Channel auth check', ['user' => $user, 'id' => $id]);
    return (int) $user->id === (int) $id;
});
