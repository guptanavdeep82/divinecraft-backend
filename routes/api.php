<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/home', [CatalogController::class, 'home']);
Route::get('/categories', [CatalogController::class, 'categories']);
Route::get('/products', [CatalogController::class, 'products']);
Route::get('/products/{slug}', [CatalogController::class, 'product']);
Route::get('/settings', [CatalogController::class, 'settings']);
Route::get('/pages/{slug}', [CatalogController::class, 'page']);
Route::get('/faqs', [CatalogController::class, 'faqs']);
Route::get('/search', [CatalogController::class, 'search']);
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{slug}', [BlogController::class, 'show']);
Route::post('/enquiries', [CatalogController::class, 'enquire']);
Route::post('/orders', [OrderController::class, 'store']);
Route::post('/payments/verify', [OrderController::class, 'verify']);
Route::post('/track', [OrderController::class, 'track']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('customer')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/account/orders', [AuthController::class, 'orders']);
});
