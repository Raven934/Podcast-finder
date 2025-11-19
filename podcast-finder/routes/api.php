<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EpisodeController;
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
Route::post('/logout', [AuthController::class,'logout'])->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    // --- USERS ---
    Route::get('/allusers', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);     
    Route::put('/updateusers/{id}', [UserController::class, 'update']);
    Route::delete('/deleteusers/{id}', [UserController::class, 'destroy']);
});


/*
|--------------------------------------------------------------------------
| HOST ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:host'])->group(function () {

    // PODCASTS
    Route::post('/podcasts', [PodcastController::class, 'store']);
    Route::put('/updatepodcasts/{id}', [PodcastController::class, 'update']);
    Route::get('/podcasts/{id}', [PodcastController::class, 'show']);

    // EPISODES 
    Route::get('/allepisodes', [EpisodeController::class, 'index']);
    Route::post('/episodes', [EpisodeController::class, 'store']);
    Route::get('/episode/{id}', [EpisodeController::class, 'show']); 
    Route::put('/updateepisodes/{id}', [EpisodeController::class, 'update']); 
    Route::delete('/deleteepisodes/{id}', [EpisodeController::class, 'destroy']); 
});




