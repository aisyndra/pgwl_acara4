<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\PolygonsController;
use App\Http\Controllers\PolylinesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/points', [ApiController::class, 'points'])->name('api.points');
Route::get('/polylines', [PolylinesController::class, 'index'])->name('api.polylines');
Route::post('/polylines', [PolylinesController::class, 'store']);
Route::get('/polylines/{id}', [PolylinesController::class, 'show']);
Route::delete('/polylines/{id}', [PolylinesController::class, 'destroy']);
Route::get('/polygon', [PolygonsController::class, 'index'])->name('api.polygon');
