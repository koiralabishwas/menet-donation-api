<?php

use App\Http\Controllers\CheckoutSessionController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\XServerController;
use Illuminate\Support\Facades\Route;

Route::post('/checkout-session', [CheckoutSessionController::class, 'create']);
Route::post('/webhook', [WebhookController::class, 'create']);

Route::post('/xserver/check-if-email-exists', [XServerController::class, 'check_if_email_exists']);

//use for debugging functions or routes
Route::post('/debug/createCustomer', [DebugController::class, 'checkCreateCustomer']);
Route::get('/debug/email', [DebugController::class, 'getDbCustomerObjFromEmail']);
Route::get('/debug/product-name', [DebugController::class, 'getStripeProductNameFromProductId']);
