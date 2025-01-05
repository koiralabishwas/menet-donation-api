<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/pdf/{donor_external_id}/{year}', [PdfController::class, 'create']);
