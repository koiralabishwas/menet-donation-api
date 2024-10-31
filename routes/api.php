<?php

use App\Http\Controllers\CheckoutSessionController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\DonationImageController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/checkout-session', [CheckoutSessionController::class, 'create']);

Route::prefix('/webhooks')->group(function () {
    Route::post('/payment-intent-succeed', [WebhookController::class, 'paymentIntentSucceed']);
});
Route::post('/webhook', [WebhookController::class, 'create']);

Route::prefix('/images')->group(function () {
    Route::post('/upload-donation-image', [DonationImageController::class, 'uploadDonationImage']);
    Route::delete('/delete-donation-image/{image_id}', [DonationImageController::class, 'deleteDonationImage']);
});

//use for debugging functions or routes
Route::post('/debug/createCustomer', [DebugController::class, 'checkCreateCustomer']);
Route::get('/debug/email', [DebugController::class, 'getDbCustomerObjFromEmail']);
Route::get('/debug/product-name', [DebugController::class, 'getStripeProductNameFromProductId']);
