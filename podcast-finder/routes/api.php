<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/allpodcasts', [PodcastController::class, 'index']);
Route::post('/logout', [AuthController::class,'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/allusers', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/updateusers/{id}', [UserController::class, 'update']);
    Route::delete('/deleteusers/{id}', [UserController::class,'destroy']);
    Route::delete('/deletepodcast/{id}', [PodcastController::class,'destroy']);
});

Route::middleware(['auth:sanctum', 'role:host'])->group(function () {
    Route::post('/podcasts', [PodcastController::class, 'store']);
    Route::put('/updatepodcasts/{id}', [PodcastController::class, 'update']);
});



