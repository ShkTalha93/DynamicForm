<?php

use App\Http\Controllers\JsonController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('cors')->group(function () {
    
// });

Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login'])->middleware('check');
Route::post('/data', [JsonController::class, 'store']);
Route::get('/getdata', [JsonController::class, 'get']);
Route::get('/share-json/{id}', [JsonController::class, 'shareJson']);
Route::get('/display-json', [JsonController::class, 'displayJson'])->name('json.share');
Route::delete('/delete/{id}', [JsonController::class, 'delete']);
Route::delete('/reject/{id}', [JsonController::class, 'reject']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});