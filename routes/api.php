<?php

use App\Http\Controllers\CheckoutSessionController;
use App\Http\Controllers\DonorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/donor',[DonorController::class,'index']);

Route::get('/checkout-session',[CheckoutSessionController::class,'create']);
