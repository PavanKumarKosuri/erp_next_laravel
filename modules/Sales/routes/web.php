<?php

use Illuminate\Support\Facades\Route;
use Modules\Sales\Http\Controllers\SalesController;

Route::middleware(['auth'])->group(function () {
    Route::get('/Sales', [SalesController::class, 'index'])->name('sales.index');
});