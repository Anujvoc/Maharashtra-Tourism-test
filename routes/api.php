<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\frontend\Api\TrackController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/test', function (Request $request) {
    return "test";
});

Route::post('/track-application-status', [TrackController::class, 'trackStatus']);

// API for Aaple Sarkar RTS Dashboard Integration
Route::get('/rts/tourism-dashboard', [TrackController::class, 'index']);


