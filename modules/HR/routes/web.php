<?php

use Illuminate\Support\Facades\Route;
use Modules\HR\Http\Controllers\HRController;

Route::middleware(['auth'])->group(function () {
    Route::get('/HR', [HRController::class, 'index'])->name('hr.index');
});