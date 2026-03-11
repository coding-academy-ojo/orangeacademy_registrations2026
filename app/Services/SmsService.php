<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $this->from = config('services.twilio.from');

        if ($sid && $token) {
            $this->client = new \Twilio\Rest\Client($sid, $token);
        }
    }

    public function send($to, $message)
    {
        if (!$this->client) {
            Log::error("Twilio client not initialized. Check .env credentials.");
            return false;
        }

        try {
            $this->client->messages->create(
                $to,
                [
                    'from' => $this->from,
                    'body' => $message
                ]
            );
            return true;
        } catch (\Exception $e) {
            Log::error("Twilio SMS send failed: " . $e->getMessage());
            return false;
        }
    }
}
