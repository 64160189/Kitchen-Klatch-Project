<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\postcontroller;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\FollowerControler;

Route::get('/', [postcontroller::class,'showPost'])->name('home');

Auth::routes();

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Password Reset Routes
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Email Verification Routes
Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

// Admin Routes
Route::get('/admin/home', [HomeController::class, 'adminHome'])
    ->name('admin.home')
    ->middleware(IsAdmin::class);

// Require login for creating and storing posts
Route::middleware(['auth'])->group(function () {
    Route::get('/create_post', function () {
        return view('posts/create_post');
    });
    Route::post('/insert_post', [PostController::class, 'storePost'])->name('post.store');
});

// Delete & Edit Post
Route::middleware(['auth'])->group(function () {
    Route::delete('delete_post/{id}', [PostController::class, 'deletePost'])->name('post.destroy');
    Route::get('edit_post/{id}', [PostController::class, 'editPost'])->name('post.edit');
    Route::put('/update_post/{id}', [PostController::class, 'updatePost'])->name('post.update');
});
// post routes
Route::get('/posts', [postcontroller::class, 'fetchPosts']);
Route::get('/post/{id}', [postcontroller::class, 'showFullPost'])->name('post.show');

// Users Routes
Route::resource('users', UserController::class)->only(['show', 'edit', 'update'])->middleware('auth');
Route::get('profile', [UserController::class,'profile'])->middleware('auth')->name('profile');

Route::post('users/{user}/follow',[FollowerControler::class,'follow'])->middleware('auth')->name('users.follow');
Route::post('users/{user}/unfollow',[FollowerControler::class,'unfollow'])->middleware('auth')->name('users.unfollow');

// Search Routes
Route::get('/search',[postcontroller::class,'titleSearch'])->name('title.search');