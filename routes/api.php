<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

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

// Author Routes
Route::prefix('authors')->group(function () {
    Route::get('/', [AuthorController::class, 'index']); // Get all authors
    Route::post('/', [AuthorController::class, 'store']); // Create a new author
    Route::get('{id}', [AuthorController::class, 'show']); // Get a specific author by ID
    Route::put('{id}', [AuthorController::class, 'update']); // Update a specific author
    Route::delete('{id}', [AuthorController::class, 'destroy']); // Delete a specific author
});

// Post Routes
Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index']); // Get all posts
    Route::post('/', [PostController::class, 'store']); // Create a new post
    Route::get('{id}', [PostController::class, 'show']); // Get a specific post by ID
    Route::put('{id}', [PostController::class, 'update']); // Update a specific post
    Route::delete('{id}', [PostController::class, 'destroy']); // Delete a specific post
});

// Comment Routes
Route::prefix('comments')->group(function () {
    Route::get('/', [CommentController::class, 'index']); // Get all comments
    Route::post('/', [CommentController::class, 'store']); // Create a new comment
    Route::get('{id}', [CommentController::class, 'show']); // Get a specific comment by ID
    Route::put('{id}', [CommentController::class, 'update']); // Update a specific comment
    Route::delete('{id}', [CommentController::class, 'destroy']); // Delete a specific comment
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
