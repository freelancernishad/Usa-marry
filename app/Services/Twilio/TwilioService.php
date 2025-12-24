<?php

namespace App\Services\Twilio;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $client;
  /**
     * Default country (ISO code)
     * Change if needed
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
        $this->client = new Client(config('TWILIO_SID'), config('TWILIO_AUTH_TOKEN'));
    }

    /**
     * Send SMS globally
     *
     * @param string $to   Recipient number with country code, e.g. +8801XXXXXXXXX
     * @param string $message
     * @return bool
     */
    public function sendSMS(string $to, string $message, ?string $country = null): bool
    {
        try {
            // Format & validate phone number
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
     */
    private function formatPhoneNumber(string $number, string $country): string
    {
        // Remove spaces, dashes, brackets
        $number = preg_replace('/[^0-9+]/', '', $number);

        // If already international → just validate
        if (str_starts_with($number, '+')) {
            return $this->validateE164($number);
        }

        // Country support check
        if (!isset($this->countryCodes[$country])) {
            throw new \InvalidArgumentException('Unsupported country: ' . $country);
        }

        // Bangladesh local number (starts with 0)
        if ($country === 'BD' && str_starts_with($number, '0')) {
            $number = substr($number, 1);
        }

        // Add country code
        $number = $this->countryCodes[$country] . $number;

        return $this->validateE164($number);
    }

    /**
     * Generic E.164 validation
     */
    private function validateE164(string $number): string
    {
        // E.164: + followed by 10–15 digits
        if (!preg_match('/^\+[1-9]\d{9,14}$/', $number)) {
            throw new \InvalidArgumentException('Invalid phone number format: ' . $number);
        }

        return $number;
    }

}
