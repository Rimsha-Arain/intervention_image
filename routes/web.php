<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(ImageController::class)->group(function() {
    Route::get('image', 'fileUpload');
    Route::post('image', 'storeImage')->name('image.store');
});
