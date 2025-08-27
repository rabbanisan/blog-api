<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;

// Rute Publik (tidak perlu login)
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post:slug}', [PostController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category:slug}', [CategoryController::class, 'show']);

// Rute untuk Otentikasi
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Rute yang memerlukan otentikasi
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rute untuk post dengan otorisasi peran admin atau author
    Route::middleware('role:admin,author')->group(function () {
        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{post:slug}', [PostController::class, 'update']);
        Route::delete('/posts/{post:slug}', [PostController::class, 'destroy']);
    });

    // Rute untuk kategori dengan otorisasi admin
    Route::middleware('role:admin')->group(function () {
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category:slug}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category:slug}', [CategoryController::class, 'destroy']);
    });
});

// Middleware kustom untuk memeriksa peran
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
