<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShipmentController;

Route::middleware(['auth', 'verified'])
    ->name('shipments.')
    ->group(function () {
        Route::get('shipments/', [ShipmentController::class, 'index'])->name('index');
        Route::get('shipments/create', [ShipmentController::class, 'create'])->name('create');
        Route::get('shipments/{id}', [ShipmentController::class, 'show'])->name('show');
        Route::post('shipments/', [ShipmentController::class, 'store'])->name('store');
    });