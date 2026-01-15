<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Gateway\Checkout\CheckoutWebhookController;


Route::post(
    '/checkout/webhook',
    [CheckoutWebhookController::class, 'webhook']
)->name('checkout.webhook');
