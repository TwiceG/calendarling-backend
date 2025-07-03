<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmailService
{
    protected $serviceId;
    protected $templateId;
    protected $userId;
    protected $apiKey;
    protected $pwResetTemplateId;

    public function __construct()
    {
        $this->serviceId = env('EMAILJS_SERVICE_ID');
        $this->templateId = env('EMAILJS_TEMPLATE_ID');
        $this->userId = env('EMAILJS_USER_ID');
        $this->apiKey = env('EMAILJS_API_KEY');
        $this->pwResetTemplateId = env('EMAILJS_PW_TEMPLATE_ID');
    }


    public function sendPasswordResetLink(string $fullMessage, string $date, string $userEmail, string $userName): bool
    {
        if (!$this->apiKey) {
            Log::error('Email API Key is missing in the environment variables.');
            return false;
        }

        // Prepare the payload to send via EmailJS
        $payload = [
            'service_id' => $this->serviceId,
            'template_id' => $this->pwResetTemplateId,
            'user_id' => $this->userId,
            'template_params' => [
                'to_email' => $userEmail,
                'message' => $fullMessage,
                'date' => $date,
                'name' => $userName
            ],
            'accessToken' => $this->apiKey,
        ];

        // Send the email using HTTP request to the EmailJS API
        $response = Http::post('https://api.emailjs.com/api/v1.0/email/send', $payload);

        if ($response->successful()) {
            Log::info('Email sent successfully to ' . $userEmail);
            return true;
        }

        Log::error('Failed to send email: ' . $response->body());
        return false;
    }


    public function sendNoteEmail(string $note, string $date, string $userEmail, string $userName): bool
    {
        if (!$this->apiKey) {
            Log::error('Email API Key is missing in the environment variables.');
            return false;
        }

        // Prepare the payload to send via EmailJS
        $payload = [
            'service_id' => $this->serviceId,
            'template_id' => $this->templateId,
            'user_id' => $this->userId,
            'template_params' => [
                'to_email' => $userEmail,
                'note' => $note,
                'date' => $date,
                'name' => $userName
            ],
            'accessToken' => $this->apiKey,
        ];

        // Send the email using HTTP request to the EmailJS API
        $response = Http::post('https://api.emailjs.com/api/v1.0/email/send', $payload);

        if ($response->successful()) {
            Log::info('Email sent successfully to ' . $userEmail);
            return true;
        }

        Log::error('Failed to send email: ' . $response->body());
        return false;
    }
}
