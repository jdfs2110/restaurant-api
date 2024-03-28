<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function() {
   //protected endpoints
    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::get('/usuarios', [UserController::class, 'index']);
