<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRecordController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/user_records', [UserRecordController::class, 'getUserdata']);
Route::post('/user_records', [UserRecordController::class, 'addUserRecord']);
