<?php

use App\Http\Controllers\JsonController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('cors')->group(function () {

// });


Route::middleware('auth:api')->group(function () {

    Route::post('logout', [UserController::class, 'logout']);
    Route::post('/data', [JsonController::class, 'store']);
    // Route::get('/getdata', [JsonController::class, 'get']);
    Route::get('/share-json/{id}', [JsonController::class, 'shareJson']);
    Route::delete('/delete/{id}', [JsonController::class, 'delete']);
    Route::delete('/reject/{id}', [JsonController::class, 'reject']);
    Route::get('/getdata', [JsonController::class, 'get']);
    Route::get('/getuser', [UserController::class, 'user']);
});
Route::get('/display-json/{token}', [JsonController::class, 'displayJson'])->name('json.share');
Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login'])->middleware('check');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});