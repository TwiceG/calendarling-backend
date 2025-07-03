<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\EmailService;

class EmailJSChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toCustomEmailjs')) {
            return;
        }

        $data = $notification->toCustomEmailjs($notifiable);

        $emailService = new EmailService();

        $note = 'Here is your password reset link:';
        $date = now()->toDateTimeString(); // or blank
        $userEmail = $data['email'];
        $userName = $data['name'];
        $link = $data['reset_url'];

        $fullMessage = $note . "\n" . $link;

        $emailService->sendNoteEmail($fullMessage, $date, $userEmail, $userName);
    }
}
