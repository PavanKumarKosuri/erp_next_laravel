<?php

use Illuminate\Support\Facades\Route;
use Modules\Purchasing\Http\Controllers\PurchasingController;

Route::middleware(['auth'])->group(function () {
    Route::get('/Purchasing', [PurchasingController::class, 'index'])->name('purchasing.index');
});