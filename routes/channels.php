<?php

use Illuminate\Support\Facades\Broadcast;

// Authorization for shared chat channels between 2 specific users
Broadcast::channel('chat.{userIds}', function ($user, $userIds) {
    // Parse the user IDs from the channel name (e.g., "1-2" -> [1, 2])
    $participantIds = explode('-', $userIds);

    // Convert to integers
    $participantIds = array_map('intval', $participantIds);

    // Check if the authenticated user is one of the participants
    if (!in_array($user->id, $participantIds)) {
        return false;
    }

    // Ensure we have exactly 2 participants
    if (count($participantIds) !== 2) {
        return false;
    }

    // Return user data for the channel
    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});
