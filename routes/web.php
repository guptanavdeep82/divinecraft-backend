<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VideoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'show'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [StoreController::class, 'dashboard'])->name('dashboard');

    Route::get('/products', [StoreController::class, 'products'])->name('products');
    Route::get('/products/create', [StoreController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [StoreController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [StoreController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [StoreController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [StoreController::class, 'destroyProduct'])->name('products.destroy');

    Route::get('/banners', [StoreController::class, 'banners'])->name('banners');
    Route::get('/banners/create', [StoreController::class, 'createBanner'])->name('banners.create');
    Route::post('/banners', [StoreController::class, 'storeBanner'])->name('banners.store');
    Route::get('/banners/{banner}/edit', [StoreController::class, 'editBanner'])->name('banners.edit');
    Route::put('/banners/{banner}', [StoreController::class, 'updateBanner'])->name('banners.update');
    Route::delete('/banners/{banner}', [StoreController::class, 'destroyBanner'])->name('banners.destroy');

    Route::get('/categories', [StoreController::class, 'categories'])->name('categories');
    Route::post('/categories', [StoreController::class, 'storeCategory'])->name('categories.store');
    Route::delete('/categories/{category}', [StoreController::class, 'destroyCategory'])->name('categories.destroy');

    Route::get('/orders', [OrderController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/ship', [OrderController::class, 'ship'])->name('orders.ship');
    Route::post('/orders/{order}/track', [OrderController::class, 'refresh'])->name('orders.track');
    Route::get('/payments', [OrderController::class, 'payments'])->name('payments');
    Route::get('/reports', [OrderController::class, 'report'])->name('reports');

    Route::get('/users', [UserController::class, 'index'])->name('users');

    Route::get('/blogs', [BlogController::class, 'index'])->name('blogs');
    Route::get('/blogs/create', [BlogController::class, 'create'])->name('blogs.create');
    Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');
    Route::get('/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
    Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
    Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/videos', [VideoController::class, 'index'])->name('videos');
    Route::get('/videos/create', [VideoController::class, 'create'])->name('videos.create');
    Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');
    Route::get('/videos/{video}/edit', [VideoController::class, 'edit'])->name('videos.edit');
    Route::put('/videos/{video}', [VideoController::class, 'update'])->name('videos.update');
    Route::delete('/videos/{video}', [VideoController::class, 'destroy'])->name('videos.destroy');

    Route::get('/pages', [PageController::class, 'index'])->name('pages');
    Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
    Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
    Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
    Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');

    Route::get('/faqs', [FaqController::class, 'index'])->name('faqs');
    Route::get('/faqs/create', [FaqController::class, 'create'])->name('faqs.create');
    Route::post('/faqs', [FaqController::class, 'store'])->name('faqs.store');
    Route::get('/faqs/{faq}/edit', [FaqController::class, 'edit'])->name('faqs.edit');
    Route::put('/faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');

    Route::get('/enquiries', [StoreController::class, 'enquiries'])->name('enquiries');
    Route::put('/enquiries/{enquiry}', [StoreController::class, 'updateEnquiry'])->name('enquiries.update');

    Route::get('/settings', [StoreController::class, 'settings'])->name('settings');
    Route::put('/settings', [StoreController::class, 'updateSettings'])->name('settings.update');
});
