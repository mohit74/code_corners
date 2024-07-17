<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Web API
Route::controller(AuthController::class)->group(function(){
    Route::post('login', 'login');                                                      
    Route::post('register', 'register');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/blog/index', [BlogController::class, 'index']);
    Route::post('/blog/store', [BlogController::class, 'store']);
    Route::post('/blog/edit/{id}', [BlogController::class, 'update']);
    Route::delete('/blog/delete/{id}', [BlogController::class, 'destroy']);
});