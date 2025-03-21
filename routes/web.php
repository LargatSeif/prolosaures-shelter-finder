<?php

use App\Http\Controllers\ShelterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('shelter_form');
});

Route::post('/calculate', [ShelterController::class, 'calculate'])->name('calculate');