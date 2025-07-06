<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QueryRepositories\MessageRepository;

class ChatController extends Controller
{
    protected $messageRepo;

    public function __construct(MessageRepository $messageRepo)
    {
        $this->messageRepo = $messageRepo;
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'receiver_id' => 'required|integer|exists:users,id',
        ]);

        $sender = $request->user();
        $messageText = $request->input('message');
        $helpdeskMemberId = 11;

        $receiverId = $sender->id === $helpdeskMemberId
            ? $request->input('receiver_id')
            : $helpdeskMemberId;

        // ✅ Save the message
        $message = $this->messageRepo->storeMessage($sender->id, $receiverId, $messageText);

        // ✅ Broadcast it
        broadcast(new PrivateMessageSent($message))->toOthers();

        return response()->json(['status' => 'Message sent', 'message' => $message]);
    }
}
