<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/pdf/{donor_external_id}' , [PdfController::class , 'create']);

