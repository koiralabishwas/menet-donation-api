<?php

use App\Http\Controllers\CheckoutSessionController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/donor',[DonorController::class,'index']);
Route::get('donor/{donor_external_id}',[DonorController::class,'get']);
Route::post('/donor',[DonorController::class,'create']);

Route::post('/checkout-session',[CheckoutSessionController::class,'create']);

Route::post('/webhook',[WebhookController::class,'create'] );

//use for debugging functions or routes
Route::post('/debug/createCustomer' ,[DebugController::class,'checkCreateCustomer'] );
Route::get('/debug/email' ,[DebugController::class,'getDbCustomerObjFromEmail'] );

