<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\PrivateMessageSent;
use App\Models\QueryRepositories\MessageRepository;

class ChatController extends Controller
{
    protected $messageRepo;
    protected $helpdeskMemberId;

    public function __construct(MessageRepository $messageRepo)
    {
        $this->messageRepo = $messageRepo;
        $this->helpdeskMemberId = 11;
    }
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'receiver_id' => 'required|integer|exists:users,id', // Add validation for receiver
        ]);

        $sender = $request->user();
        $message = $request->input('message');
        $helpdeskMemberId = 11;

        // Determine the receiver based on who is sending
        if ($sender->id == $helpdeskMemberId) {
            // If helpdesk agent is sending, send to the selected user
            $receiverId = $request->input('receiver_id');
        } else {
            // If regular user is sending, send to helpdesk
            $receiverId = $helpdeskMemberId;
        }

        // // ✅ Save the message
        // $message = $this->messageRepo->storeMessage($sender->id, $receiverId, $messageText);

        // Broadcast to the receiver's private channel
        broadcast(new PrivateMessageSent($sender, $receiverId, $message))->toOthers();

        return response()->json(['status' => 'Message sent']);
    }

    public function getMessagesByChannel(Request $request)
    {
        $userId = $request->user()->id;


        $channelName = $this->messageRepo->getChannelName($userId, $this->helpdeskMemberId);

        $messages = $this->messageRepo->getMessagesByChannel($channelName);

        return response()->json(['status' => 'Messages loaded', 'messages' => $messages]);
    }
}
