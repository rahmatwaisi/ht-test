<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'auth'], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});



Route::middleware('auth:sanctum')->prefix('tasks')->group(function () {

    Route::post('/', [TaskController::class, 'store']);
    Route::put('/', [TaskController::class, 'bulkUpdate']);
    Route::get('/{id}', [TaskController::class, 'show']);
    Route::get('/', [TaskController::class, 'index']);

});
