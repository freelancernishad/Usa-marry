<?php

namespace App\Services\Twilio;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TwilioService
{
    protected Client $client;

    /**
     * Messaging Service Friendly Name
     */
    protected string $messagingServiceName = 'USAMARRY';

    /**
     * Default country
     */
    protected string $defaultCountry = 'US';

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
     * Send SMS using Messaging Service NAME
     */
    public function sendSMS(string $to, string $message, ?string $country = null): bool
    {
        try {
            $to = $this->formatPhoneNumber($to, $country ?? $this->defaultCountry);
            $messagingServiceSid = $this->getMessagingServiceSid();

            Log::info('Sending Twilio SMS', [
                'to' => $to,
                'service' => $this->messagingServiceName,
                'sid' => $messagingServiceSid,
            ]);

            $this->client->messages->create($to, [
                'messagingServiceSid' => $messagingServiceSid,
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
     * Resolve Messaging Service SID from Friendly Name (cached)
     */
 private function getMessagingServiceSid(): string
{
    return Cache::rememberForever(
        'twilio.messaging_service_sid.' . $this->messagingServiceName,
        function () {
            // Correct read() usage
            $services = $this->client->messaging->services->read(20);

            foreach ($services as $service) {
                if ($service->friendlyName === $this->messagingServiceName) {
                    return $service->sid; // MGxxxx
                }
            }

            throw new \RuntimeException(
                'Messaging Service not found: ' . $this->messagingServiceName
            );
        }
    );
}


    /**
     * Phone number normalization
     */
    private function formatPhoneNumber(string $number, string $country): string
    {
        $number = preg_replace('/[^0-9+]/', '', $number);

        if (str_starts_with($number, '+')) {
            return $this->validateE164($number);
        }

        foreach ($this->countryCodes as $code) {
            $plainCode = ltrim($code, '+');
            if (str_starts_with($number, $plainCode)) {
                return $this->validateE164('+' . $number);
            }
        }

        if (!isset($this->countryCodes[$country])) {
            throw new \InvalidArgumentException('Unsupported country: ' . $country);
        }

        if ($country === 'BD' && str_starts_with($number, '0')) {
            $number = substr($number, 1);
        }

        return $this->validateE164($this->countryCodes[$country] . $number);
    }

    private function validateE164(string $number): string
    {
        if (!preg_match('/^\+[1-9]\d{9,14}$/', $number)) {
            throw new \InvalidArgumentException('Invalid phone number format: ' . $number);
        }

        return $number;
    }
}
