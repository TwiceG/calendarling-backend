<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            'receiver_id' => 'required|integer|exists:users,id',
        ]);

        $sender = $request->user();
        $messageText = $request->input('message');


        $receiverId = $sender->id === $this->helpdeskMemberId
            ? $request->input('receiver_id')
            : $this->helpdeskMemberId;

        // ✅ Save the message
        $message = $this->messageRepo->storeMessage($sender->id, $receiverId, $messageText);

        // ✅ Broadcast it
        broadcast(new PrivateMessageSent($message))->toOthers();

        return response()->json(['status' => 'Message sent', 'message' => $message]);
    }
    public function getMessagesByChannel(Request $request)
    {
        $userId = $request->user()->id;


        $channelName = $this->messageRepo->getChannelName($userId, $this->helpdeskMemberId);

        $messages = $this->messageRepo->getMessagesByChannel($channelName);

        return response()->json(['status' => 'Messages loaded', 'messages' => $messages]);
    }
}
