<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\FinanceController;

Route::middleware(['auth'])->group(function () {
    Route::get('/Finance', [FinanceController::class, 'index'])->name('finance.index');
});