<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CustomResetPasswordNotification extends Notification
{
    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['custom_emailjs'];
    }

    public function toCustomEmailjs($notifiable)
    {
        $params = http_build_query([
            'token' => $this->token,
            'email' => $notifiable->email,
        ]);

        $url = url(config('app.frontend_url') . "/reset-password?$params");

        return [
            'email' => $notifiable->email,
            'name' => $notifiable->name ?? 'User',
            'reset_url' => $url,
        ];
    }
}
