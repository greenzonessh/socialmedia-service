<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileFollowingController;
use App\Http\Controllers\PostController;

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

    Route::post('/post/showbyprofile', [PostController::class, 'showByProfile']);
    Route::post('/post/release', [PostController::class, 'postRelease']);
    Route::post('/post/showbypost', [PostController::class, 'showByPost']);
    Route::patch('/post/editCaption', [PostController::class, 'updateCaption']);
    Route::post('/post/like', [PostController::class, 'postLike']);
    Route::post('/post/unlike', [PostController::class, 'postUnlike']);
    Route::post('/post/comment', [PostController::class, 'postComment']);
});
