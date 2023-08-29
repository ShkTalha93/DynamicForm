<?php

use App\Http\Controllers\JsonController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login'])->middleware('check');
Route::post('/data', [JsonController::class, 'store']);
Route::get('/getdata', [JsonController::class, 'get']);
Route::get('/share-json/{id}', [JsonController::class, 'shareJson']);
Route::get('/display-json', [JsonController::class, 'displayJson'])->name('json.share');
Route::delete('/delete/{id}', [JsonController::class, 'delete']);
Route::delete('/reject/{id}', [JsonController::class, 'reject']);