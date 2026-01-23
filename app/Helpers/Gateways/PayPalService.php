<?php

namespace App\Helpers\Gateways;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PayPalService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('paypal.base_uri'),
            'auth' => [
                config('paypal.client_id'),
                config('paypal.client_secret'),
            ],
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Create Order & Return Approval URL
     */
    public function createOrder(array $payload): array
    {
        $response = $this->request('POST', 'v2/checkout/orders', $payload);

        if (!($response['success'] ?? false)) {
            return $response;
        }

        $approvalUrl = null;

        if (!empty($response['data']['links'])) {
            foreach ($response['data']['links'] as $link) {
                if (in_array($link['rel'], ['approve', 'payer-action'])) {
                    $approvalUrl = $link['href'];
                    break;
                }
            }
        }

        return [
            'success' => true,
            'order_id' => $response['data']['id'] ?? null,
            'status' => $response['data']['status'] ?? null,
            'approval_url' => $approvalUrl, // ✅ redirect URL
            'raw' => $response['data'],      // full PayPal response (optional)
        ];
    }


    /**
     * Get Order Details
     */
    public function getOrder(string $orderId): array
    {
        return $this->request('GET', "v2/checkout/orders/{$orderId}");
    }

    /**
     * Capture Order
     */
    public function captureOrder(string $orderId): array
    {
        return $this->request('POST', "v2/checkout/orders/{$orderId}/capture");
    }

    /**
     * Authorize Order (authorize funds)
     */
    public function authorizeOrder(string $orderId): array
    {
        return $this->request('POST', "v2/checkout/orders/{$orderId}/authorize", []);
    }

    /**
     * Void an Order (cancel)
     */
    public function voidOrder(string $orderId): array
    {
        return $this->request('POST', "v2/checkout/orders/{$orderId}/void", []);
    }

    /**
     * Update Order (PATCH)
     */
    public function updateOrder(string $orderId, array $patchData): array
    {
        return $this->request('PATCH', "v2/checkout/orders/{$orderId}", $patchData);
    }

    /**
     * Common request handler
     */
    private function request(string $method, string $uri, array $body = []): array
    {
        try {
            $options = [];
            if (!empty($body)) {
                $options['json'] = $body;
            }

            $response = $this->client->request($method, $uri, $options);

            return [
                'success' => true,
                'status' => $response->getStatusCode(),
                'data' => json_decode($response->getBody(), true),
            ];

        } catch (RequestException $e) {
            return [
                'success' => false,
                'status' => $e->getResponse()
                    ? $e->getResponse()->getStatusCode()
                    : null,
                'error' => $e->getMessage(),
                'body' => $e->getResponse()
                    ? $e->getResponse()->getBody()->getContents()
                    : null,
            ];
        }
    }
}
