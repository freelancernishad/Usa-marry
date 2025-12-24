<?php

namespace App\Services\Twilio;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected Client $client;

    /**
     * Default country (ISO code)
     */
    protected string $defaultCountry = 'US';

    /**
     * Supported countries
     */
    protected array $countryCodes = [
        'BD' => '+880',
        'IN' => '+91',
        'US' => '+1',
        'UK' => '+44',
    ];

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');

        if (!$sid || !$token) {
            throw new \RuntimeException('Twilio credentials are missing');
        }

        $this->client = new Client($sid, $token);
    }

    /**
     * Send SMS
     */
    public function sendSMS(string $to, string $message, ?string $country = null): bool
    {
        try {
            $to = $this->formatPhoneNumber($to, $country ?? $this->defaultCountry);

            Log::info('Sending Twilio SMS', ['to' => $to]);

            $this->client->messages->create($to, [
                'from' => config('services.twilio.from'),
                'body' => $message,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('Twilio SMS Error', [
                'error' => $e->getMessage(),
                'to' => $to ?? null,
            ]);

            return false;
        }
    }

    /**
     * Normalize + validate phone number
     *
     * Rules:
     * 1. + থাকলে → unchanged
     * 2. country code আছে কিন্তু + নাই → only + add
     * 3. country code নাই → + & country code add
     */
    private function formatPhoneNumber(string $number, string $country): string
    {
        // Remove spaces, dashes, brackets
        $number = preg_replace('/[^0-9+]/', '', $number);

        // 1️⃣ Already has +
        if (str_starts_with($number, '+')) {
            return $this->validateE164($number);
        }

        // Country support check
        if (!isset($this->countryCodes[$country])) {
            throw new \InvalidArgumentException('Unsupported country: ' . $country);
        }

        $countryCode = ltrim($this->countryCodes[$country], '+'); // e.g. 1, 880

        // 2️⃣ Has country code but missing +
        if (str_starts_with($number, $countryCode)) {
            return $this->validateE164('+' . $number);
        }

        // 3️⃣ Bangladesh local number (starts with 0)
        if ($country === 'BD' && str_starts_with($number, '0')) {
            $number = substr($number, 1);
        }

        // 4️⃣ No country code → add + & country code
        return $this->validateE164($this->countryCodes[$country] . $number);
    }

    /**
     * Generic E.164 validation
     */
    private function validateE164(string $number): string
    {
        if (!preg_match('/^\+[1-9]\d{9,14}$/', $number)) {
            throw new \InvalidArgumentException('Invalid phone number format: ' . $number);
        }

        return $number;
    }
}
