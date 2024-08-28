<?php
use App\Http\Controllers\DonorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::resource('donor',DonorController::class);
// ↑↑ will do all these
//Route::get("/donor" , [DonorController::class , "index"])->name("donor.index");
//Route::get('/donor/create',[DonorController::class , "create"])->name("donor.create");
//Route::get("/donor/{id}" , [DonorController::class , "show"])->name("donor.show");
//Route::post('/donor',[DonorController::class , "store"])->name("donor.store");
//Route::put("/donor/{id}" , [DonorController::class , "update"])->name("donor.update");
//Route::delete("/donor/{id}" , [DonorController::class , "destroy"])->name("donor.destroy");
