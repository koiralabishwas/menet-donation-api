<?php

use App\Http\Controllers\CheckoutSessionController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\DonationImageController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\XServerController;
use Illuminate\Support\Facades\Route;

Route::post('/checkout-session', [CheckoutSessionController::class, 'create']);

Route::prefix('/webhooks')->group(function () {
    Route::post('/payment-intent-succeed', [WebhookController::class, 'paymentIntentSucceed']);
});
Route::post('/webhook', [WebhookController::class, 'create']);

Route::prefix('/images')->group(function () {
    Route::post('/upload', [DonationImageController::class, 'uploadDonationImage']);
    Route::delete('/delete/{image_id}', [DonationImageController::class, 'deleteDonationImage']);
});

Route::post('/xserver/check-if-email-exists', [XServerController::class, 'check_if_email_exists']);

//use for debugging functions or routes

Route::prefix('/debug')->group(function () {
    Route::post('/createCustomer', [DebugController::class, 'checkCreateCustomer']);
    Route::get('/email', [DebugController::class, 'getDbCustomerObjFromEmail']);
    Route::get('/product-name', [DebugController::class, 'getStripeProductNameFromProductId']);
    Route::get('/price', [DebugController::class, 'getStrpePriceByID']);
});
