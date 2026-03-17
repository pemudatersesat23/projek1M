<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/programs', [\App\Http\Controllers\Api\ProgramController::class, 'index']);
Route::get('/programs/{id}', [\App\Http\Controllers\Api\ProgramController::class, 'show']);
Route::get('/programs/{id}/active-batch', [\App\Http\Controllers\Api\ProgramController::class, 'activeBatch']);
Route::post('/register', [\App\Http\Controllers\Api\RegisterController::class, 'register']);
