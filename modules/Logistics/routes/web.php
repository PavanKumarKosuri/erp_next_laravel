<?php

use Illuminate\Support\Facades\Route;
use Modules\Logistics\Http\Controllers\LogisticsController;

Route::middleware(['auth'])->group(function () {
    Route::get('/Logistics', [LogisticsController::class, 'index'])->name('logistics.index');
});