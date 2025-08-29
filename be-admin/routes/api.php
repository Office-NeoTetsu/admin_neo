<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// resource controller API
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\UserController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::apiResource('/user', UserController::class)->middleware('auth:sanctum');

