<?php

use App\Http\Controllers\CheckoutSessionController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\DonationImageController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionSessionController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\XServerController;
use Illuminate\Support\Facades\Route;

//TODO: restructure routes
// payments/
//         /onetime
//          /monthly
//          /manage
//
Route::post('/checkout-session', [CheckoutSessionController::class, 'create']);
Route::post('/subscription-session', [SubscriptionSessionController::class, 'create']);
Route::post('/subscriptions/{subscription_external_id}/cancel/', [SubscriptionController::class, 'deleteSubscription']);

Route::prefix('/webhooks')->group(function () {
    Route::post('/payment-intent-succeed', [WebhookController::class, 'paymentIntentSucceed']);
    Route::post('/customer-subscription-created', [WebhookController::class, 'customerSubscriptionCreated']);
    Route::post('/customer-subscription-deleted', [WebhookController::class, 'customerSubscriptionDeleted']);
    Route::post('/invoice-paid', [WebhookController::class, 'invoicePaid']);
});

Route::prefix('/images')->group(function () {
    Route::post('/upload', [DonationImageController::class, 'uploadDonationImage']);
    Route::delete('/delete/{image_id}', [DonationImageController::class, 'deleteDonationImage']);
});

Route::post('/xserver/check-if-email-exists', [XServerController::class, 'check_if_email_exists']);

// use for debugging functions or routes
Route::prefix('/debug')->group(function () {
    Route::post('webhooks/payment-intent-succeed', [DebugController::class, 'debugPaymentIntentSucceed']);
    Route::post('/createCustomer', [DebugController::class, 'checkCreateCustomer']);
    Route::get('/email', [DebugController::class, 'getDbCustomerObjFromEmail']);
    Route::get('/product-name', [DebugController::class, 'getStripeProductNameFromProductId']);
    Route::get('/price', [DebugController::class, 'getStrpePriceByID']);
    Route::get('/price/subs', [DebugController::class, 'createSubscriptionPrice']);
    Route::get('/webhook', [WebhookController::class, 'create']);
    Route::get('/invoice', [DebugController::class, 'getInvoice']);
    Route::get('/cancel-subscription/{subscription_id}', [DebugController::class, 'cancelSubscription']);
});
