<?php

use Illuminate\Support\Facades\Route;
use Modules\Production\Http\Controllers\ProductionController;

Route::middleware(['auth'])->group(function () {
    Route::get('/Production', [ProductionController::class, 'index'])->name('production.index');
});