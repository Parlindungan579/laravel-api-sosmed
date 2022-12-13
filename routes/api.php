<?php

use Illuminate\Support\Facades\Route;

//users
Route::apiResource('/users', App\Http\Controllers\Api\UserController::class);
//posts
Route::apiResource('/posts', App\Http\Controllers\Api\PostController::class);
//followers
Route::apiResource('/followers', App\Http\Controllers\Api\FollowerController::class);
//likes
Route::apiResource('/likes', App\Http\Controllers\Api\LikeController::class);
//comments
Route::apiResource('/comments', App\Http\Controllers\Api\CommentController::class);
