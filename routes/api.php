<?php

use App\Http\Controllers\BridgeController;
use Illuminate\Support\Facades\Route;

Route::middleware('bridge.token')->prefix('bridge')->group(function () {
    Route::get('employees',    [BridgeController::class, 'employees']);
    Route::get('templates',    [BridgeController::class, 'templates']);
    Route::post('enroll',      [BridgeController::class, 'enroll']);
    Route::post('identify',    [BridgeController::class, 'identify']);
    Route::post('clock',       [BridgeController::class, 'clock']);
    Route::post('clock-by-id', [BridgeController::class, 'clockById']);
});