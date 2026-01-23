<?php

namespace App\Helpers\Gateways;

use Exception;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Library\SslCommerz\SslCommerzNotification;

class SSLCommerz
{
    /**
     * Main checkout method
     */
    public function checkout(array $orderData)
    {
        Log::info('Initiating SSLCommerz checkout', $orderData);
        /*
        |--------------------------------------------------------------------------
        | 1. Validate Minimum Required Data
        |--------------------------------------------------------------------------
        */
        if (empty($orderData['amount']) || $orderData['amount'] < 10) {
            throw new InvalidArgumentException('Minimum payment amount is 10 BDT');
        }

        if (empty($orderData['customer'])) {
            throw new InvalidArgumentException('Customer information is required');
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Transaction Info
        |--------------------------------------------------------------------------
        */
        $post_data = [];

        $post_data['total_amount'] = $orderData['amount'];
        $post_data['currency']     = $orderData['currency'] ?? 'BDT';
        $post_data['tran_id']      = $orderData['transaction_id']
                                    ?? Str::uuid()->toString();

        /*
        |--------------------------------------------------------------------------
        | 3. Dynamic Callback URLs
        |--------------------------------------------------------------------------
        */
        $post_data['success_url'] = $this->callbackUrl($orderData, 'success', $post_data['tran_id']);
        $post_data['fail_url']    = $this->callbackUrl($orderData, 'fail', $post_data['tran_id']);
        $post_data['cancel_url']  = $this->callbackUrl($orderData, 'cancel', $post_data['tran_id']);
        $post_data['ipn_url']     = $this->callbackUrl($orderData, 'ipn', $post_data['tran_id']);

        /*
        |--------------------------------------------------------------------------
        | 4. Customer Information
        |--------------------------------------------------------------------------
        */
        $customer = $orderData['customer'];

        $post_data['cus_name']     = $customer['name'];
        $post_data['cus_email']    = $customer['email'];
        $post_data['cus_phone']    = $customer['phone'];
        $post_data['cus_add1']     = $customer['address'] ?? '';
        $post_data['cus_add2']     = '';
        $post_data['cus_city']     = $customer['city'] ?? '';
        $post_data['cus_state']    = '';
        $post_data['cus_postcode'] = '';
        $post_data['cus_country']  = $customer['country'] ?? 'Bangladesh';
        $post_data['cus_fax']      = '';

        /*
        |--------------------------------------------------------------------------
        | 5. Shipping Information (Optional)
        |--------------------------------------------------------------------------
        */
        $shipping = $orderData['shipping'] ?? [];

        $post_data['ship_name']     = $shipping['name'] ?? $post_data['cus_name'];
        $post_data['ship_add1']     = $shipping['address'] ?? $post_data['cus_add1'];
        $post_data['ship_add2']     = '';
        $post_data['ship_city']     = $shipping['city'] ?? '';
        $post_data['ship_state']    = '';
        $post_data['ship_postcode'] = '';
        $post_data['ship_country']  = $shipping['country'] ?? 'Bangladesh';
        $post_data['ship_phone']    = '';

        /*
        |--------------------------------------------------------------------------
        | 6. Product Information
        |--------------------------------------------------------------------------
        */
        $product = $orderData['product'] ?? [];

        $post_data['shipping_method']  = 'NO';
        $post_data['product_name']     = $product['name'] ?? 'Order Payment';
        $post_data['product_category'] = $product['category'] ?? 'General';
        $post_data['product_profile']  = $product['profile'] ?? 'general';

        /*
        |--------------------------------------------------------------------------
        | 7. Optional Meta / Reference Values
        |--------------------------------------------------------------------------
        */
        $meta = $orderData['meta'] ?? [];

        $post_data['value_a'] = $meta['ref_a'] ?? null;
        $post_data['value_b'] = $meta['ref_b'] ?? null;
        $post_data['value_c'] = $meta['ref_c'] ?? null;
        $post_data['value_d'] = $meta['ref_d'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | 8. Initiate SSLCommerz Payment
        |--------------------------------------------------------------------------
        */
        $sslc = new SslCommerzNotification();

        Log::info('Redirecting to SSLCommerz gateway', $post_data);
        // hosted = direct redirect to gateway
        return $sslc->makePayment($post_data, 'checkout');
    }

    /**
     * Build Dynamic Callback URL
     */
    protected function callbackUrl(array $orderData, string $type, string $tranId): string
    {
        // 1️⃣ If client/frontend sends full URL
        if (!empty($orderData['callback_urls'][$type])) {
            return $orderData['callback_urls'][$type];
        }

        // 2️⃣ Transaction-based backend URL (safe default)
        return url("/payment/{$type}/{$tranId}");
    }



        /**
     * Convert currency using fawazahmed0 currency API
     *
     * @param float  $amount
     * @param string $fromCurrency (e.g. USD)
     * @param string $toCurrency   (e.g. BDT)
     * @return float
     */
    public function convertCurrency(
        float $amount,
        string $fromCurrency,
        string $toCurrency
    ): float {
        $fromCurrency = strtolower($fromCurrency);
        $toCurrency   = strtolower($toCurrency);

        // Same currency, no conversion
        if ($fromCurrency === $toCurrency) {
            return round($amount, 2);
        }

        $cacheKey = "currency_rate_{$fromCurrency}_{$toCurrency}";

        // Cache for 6 hours
        $rate = Cache::remember($cacheKey, now()->addHours(6), function () use ($fromCurrency, $toCurrency) {

            $url = "https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/{$fromCurrency}.json";

            $response = Http::timeout(10)->get($url);

            if (!$response->successful()) {
                throw new Exception('Unable to fetch currency rate');
            }

            $data = $response->json();

            if (!isset($data[$fromCurrency][$toCurrency])) {
                throw new Exception("Currency {$toCurrency} not found in API response");
            }

            return (float) $data[$fromCurrency][$toCurrency];
        });

        return round($amount * $rate, 2);
    }

    /**
     * Example: Convert USD → BDT for SSLCommerz
     */
    public function convertToBDT(float $amount, string $fromCurrency = 'USD'): float
    {
        return $this->convertCurrency($amount, $fromCurrency, 'BDT');
    }



}
