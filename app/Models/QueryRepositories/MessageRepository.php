<?php

namespace App\Models\QueryRepositories;

use App\Models\Message;

class MessageRepository
{

    public function getChannelName($userAId, $userBId)
    {
        return 'chat:' . min($userAId, $userBId) . '-' . max($userAId, $userBId);
    }

    public function storeMessage(int $senderId, int $receiverId, string $message): Message
    {
        $channelName = $this->getChannelName($senderId, $receiverId);

        return Message::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $message,
            'channel_name' => $channelName,
        ]);
    }

    public function getMessagesBetween(int $userAId, int $userBId)
    {
        return Message::where(function ($query) use ($userAId, $userBId) {
            $query->where('sender_id', $userAId)->where('receiver_id', $userBId);
        })
            ->orWhere(function ($query) use ($userAId, $userBId) {
                $query->where('sender_id', $userBId)->where('receiver_id', $userAId);
            })
            ->orderBy('created_at')
            ->get();
    }

    public function getMessagesByChannel(string $channelName)
    {
        return Message::where('channel_name', $channelName)
            ->orderBy('created_at')
            ->get();
    }
}
