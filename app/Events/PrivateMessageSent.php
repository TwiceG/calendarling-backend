<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class PrivateMessageSent implements ShouldBroadcast
{
    use SerializesModels;

    public $sender;
    public $receiverId;
    public $message;

    public function __construct(User $sender, int $receiverId, string $message)
    {
        $this->sender = $sender;
        $this->receiverId = $receiverId;
        $this->message = $message;
    }

    // Create a shared channel for both users
    public function broadcastOn()
    {
        // Create a consistent channel name for both users
        $userIds = [$this->sender->id, $this->receiverId];
        sort($userIds); // Ensure consistent ordering
        $channelName = 'chat.' . implode('-', $userIds);

        return new PrivateChannel($channelName);
    }

    public function broadcastAs()
    {
        return 'PrivateMessageSent';
    }

    public function broadcastWith()
    {
        return [
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'message' => $this->message,
            'receiver_id' => $this->receiverId,
        ];
    }
}
