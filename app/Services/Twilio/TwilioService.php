<?php

namespace App\Services\Twilio;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TwilioService
{
    protected $client = null;

    /**
     * Default country (ISO)
     */
    protected string $defaultCountry = 'US';

    /**
     * Supported country codes
     */
    protected array $countryCodes = [
        'BD' => '+880',
        'IN' => '+91',
        'US' => '+1',
        'UK' => '+44',
    ];

    public function __construct()
    {
        $sid   = config('TWILIO_SID');
        $token = config('TWILIO_AUTH_TOKEN');

        if (class_exists('Twilio\Rest\Client') && $sid && $token) {
            $this->client = new \Twilio\Rest\Client($sid, $token);
        }
    }

    /**
     * Send SMS (Production Safe using Messaging Service)
     */
    public function sendSMS(string $to, string $message, ?string $country = null): bool
    {
        try {
            // Format & validate number
            $to = $this->formatPhoneNumber($to, $country ?? $this->defaultCountry);

            // If it is a Bangladesh number, send using SMSNOC
            if (str_starts_with($to, '+880')) {
                return $this->sendViaSmsNoc($to, $message);
            }

            // Otherwise, send using Twilio
            return $this->sendViaTwilio($to, $message);
        } catch (\Throwable $e) {
            Log::error('SMS Routing/Sending Error', [
                'error' => $e->getMessage(),
                'to'    => $to ?? null,
            ]);

            return false;
        }
    }

    /**
     * Send SMS via Twilio
     */
    protected function sendViaTwilio(string $to, string $message): bool
    {
        if (!$this->client) {
            Log::error('Twilio client is not initialized because TWILIO_SID or TWILIO_AUTH_TOKEN is missing.');
            return false;
        }

        Log::info('Sending Twilio SMS', [
            'to' => $to,
            'via' => 'messaging_service'
        ]);

        $this->client->messages->create($to, [
            'messagingServiceSid' => 'MG09d843fbd6518ceadafaef2ad4ba3a57',
            'body' => $message,
        ]);

        return true;
    }

    /**
     * Send SMS via SMSNOC API
     */
    protected function sendViaSmsNoc(string $to, string $message): bool
    {
        $apiKey = config('SMSNOC_API_KEY') ?? env('SMSNOC_API_KEY');
        $senderId = config('SMSNOC_SENDER_ID') ?? env('SMSNOC_SENDER_ID');

        if (!$apiKey || !$senderId) {
            Log::error('SMSNOC credentials are missing. Please set SMSNOC_API_KEY and SMSNOC_SENDER_ID in settings/env.');
            return false;
        }

        $recipient = ltrim($to, '+');
        $isUnicode = preg_match('/[^\x00-\x7F]/', $message);

        $payload = [
            'recipient' => $recipient,
            'sender_id' => $senderId,
            'type'      => $isUnicode ? 'unicode' : 'plain',
            'message'   => $message,
        ];

        Log::info('Sending SMS via SMSNOC', [
            'to' => $to,
        ]);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $apiKey,
        ])->post('https://app.smsnoc.com/api/v3/sms/send', $payload);

        if ($response->successful()) {
            Log::info('SMS sent successfully via SMSNOC', ['to' => $to]);
            return true;
        }

        Log::error('SMSNOC sending failed', [
            'status' => $response->status(),
            'body'   => $response->body(),
            'to'     => $to,
        ]);

        return false;
    }

    /**
     * Normalize + validate phone number
     *
     * Rules:
     * 1. + থাকলে → unchanged
     * 2. + না থাকলে কিন্তু known country code থাকলে → only +
     * 3. country code নাই → default country code add
     */
    private function formatPhoneNumber(string $number, string $country): string
    {
        // Clean input
        $number = preg_replace('/[^0-9+]/', '', $number);

        // 1️⃣ Already has +
        if (str_starts_with($number, '+')) {
            return $this->validateE164($number);
        }

        // 2️⃣ Starts with ANY known country code → just add +
        foreach ($this->countryCodes as $code) {
            $plainCode = ltrim($code, '+'); // 880, 1, 91, 44
            if (str_starts_with($number, $plainCode)) {
                return $this->validateE164('+' . $number);
            }
        }

        // 3️⃣ No country code → use default country
        if (!isset($this->countryCodes[$country])) {
            throw new \InvalidArgumentException('Unsupported country: ' . $country);
        }

        // Bangladesh local number starts with 0
        if ($country === 'BD' && str_starts_with($number, '0')) {
            $number = substr($number, 1);
        }

        return $this->validateE164($this->countryCodes[$country] . $number);
    }

    /**
     * E.164 validation
     */
    private function validateE164(string $number): string
    {
        if (!preg_match('/^\+[1-9]\d{9,14}$/', $number)) {
            throw new \InvalidArgumentException('Invalid phone number format: ' . $number);
        }

        return $number;
    }
}
