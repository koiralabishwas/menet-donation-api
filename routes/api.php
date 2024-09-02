<?php

use App\Http\Controllers\CheckoutSessionController;
use App\Http\Controllers\DonorController;
use Illuminate\Support\Facades\Route;

Route::get('/donor',[DonorController::class,'index']);
Route::post('/donor',[DonorController::class,'register']);

Route::get('/checkout-session',[CheckoutSessionController::class,'create']);
