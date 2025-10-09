<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\InventoryController;

Route::middleware(['auth'])->group(function () {
    Route::get('/Inventory', [InventoryController::class, 'index'])->name('inventory.index');
});