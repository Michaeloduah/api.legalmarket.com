<?php

use App\Http\Controllers\StorageController;
use illuminate\Support\Facades\Route;

Route::prefix("/storage")->name('storage.')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [StorageController::class, 'storage']);
    Route::post('/store', [StorageController::class, 'store']);
});
