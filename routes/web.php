<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Home', ['name' => 'Valencia']);
});

Route::get('/pdf/{donor_external_id}/{year}', [PdfController::class, 'create']);
