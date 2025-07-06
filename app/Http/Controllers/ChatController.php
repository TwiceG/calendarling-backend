<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'receiver_id' => 'required|integer', // ID of the user agent is chatting with
        ]);

        $sender = $request->user();
        $receiverId = $request->input('receiver_id');
        $message = $request->input('message');

        // Broadcast to the receiver's private channel
        broadcast(new \App\Events\PrivateMessageSent($sender, $receiverId, $message))->toOthers();

        return response()->json(['status' => 'Message sent']);
    }
}
