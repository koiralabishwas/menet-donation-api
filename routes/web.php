<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::get('/pdf/{donor_external_id}/{year}', [PdfController::class, 'create']);

Route::get('/', function () {
    return inertia('index');
});

Route::get('/projects/{project}', function (string $project) {
    return inertia('project', ['project' => $project]);
});
