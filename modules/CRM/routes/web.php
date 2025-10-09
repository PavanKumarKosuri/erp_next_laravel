<?php

use Illuminate\Support\Facades\Route;
use Modules\CRM\Http\Controllers\CRMController;

Route::middleware(['auth'])->group(function () {
    Route::get('/CRM', [CRMController::class, 'index'])->name('crm.index');
});