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

    // Broadcast on the receiver's private channel
    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->receiverId);
    }

    // Event name to listen for on frontend
    public function broadcastAs()
    {
        return 'PrivateMessageSent';
    }

    // Data sent to frontend
    public function broadcastWith()
    {
        return [
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'message' => $this->message,
        ];
    }
}
