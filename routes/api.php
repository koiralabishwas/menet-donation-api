<?php

use App\Http\Controllers\CheckoutSessionController;
use App\Http\Controllers\DonorController;
use Illuminate\Support\Facades\Route;

Route::get('/donor',[DonorController::class,'index']);
Route::get('donor/{donor_external_id}',[DonorController::class,'show']);
Route::post('/donor',[DonorController::class,'store']);
Route::get('/checkout-session',[CheckoutSessionController::class,'create']);
