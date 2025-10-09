<?php

use Illuminate\Support\Facades\Route;
use Modules\Reports\Http\Controllers\ReportsController;

Route::middleware(['auth'])->group(function () {
    Route::get('/Reports', [ReportsController::class, 'index'])->name('reports.index');
});