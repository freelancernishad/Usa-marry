<?php

namespace App\Services\Twilio;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected Client $client;

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

        if (!$sid || !$token) {
            throw new \RuntimeException('Twilio credentials are missing');
        }

        $this->client = new Client($sid, $token);
    }

    /**
     * Send SMS (Production Safe using Messaging Service)
     */
    public function sendSMS(string $to, string $message, ?string $country = null): bool
    {
        try {
            // Format & validate number
            $to = $this->formatPhoneNumber($to, $country ?? $this->defaultCountry);

            Log::info('Sending Twilio SMS', [
                'to' => $to,
                'via' => 'messaging_service'
            ]);

            $this->client->messages->create($to, [
                'messagingServiceSid' => 'MG09d843fbd6518ceadafaef2ad4ba3a57',
                'body' => $message,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('Twilio SMS Error', [
                'error' => $e->getMessage(),
                'to'    => $to ?? null,
            ]);

            return false;
        }
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
