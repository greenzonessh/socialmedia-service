<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileFollowingController;

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

Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/login', [UserAuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('/profile/show', [ProfileController::class, 'show']);
    Route::patch('/profile/update', [ProfileController::class, 'update']);
    Route::post('/profile/search', [ProfileController::class, 'search']);
    Route::post('/profile/following/add', [ProfileFollowingController::class, 'storeFollowing']);
    Route::post('/profile/following/show', [ProfileFollowingController::class, 'showFollowing']);
    Route::post('/profile/follower/show', [ProfileFollowingController::class, 'showFollower']);
});
