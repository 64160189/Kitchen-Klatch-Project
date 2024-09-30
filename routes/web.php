<?php

use App\Http\Controllers\CommentController;

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminController;


// Route สำหรับขอรีเซ็ตรหัสผ่าน
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Route สำหรับตั้งค่ารหัสผ่านใหม่
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// CommentNotification

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
});
Route::get('notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications/read/{id}', [NotificationController::class, 'read'])->name('notifications.read');



// Home Route
Route::get('/', [PostController::class, 'showPost'])->name('home');

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
Route::get('/admin/home', [AdminController::class, 'adminHome'])->name('admin.home')->middleware(IsAdmin::class);
Route::get('/admin/table/user', [AdminController::class, 'usersTable'])->name('table.user')->middleware(IsAdmin::class);
Route::get('/admin/table/post', [AdminController::class, 'postsTable'])->name('table.post')->middleware(IsAdmin::class);
Route::get('/admin/table/user/search', [AdminController::class, 'userSearch'])->name('search.user')->middleware(Isadmin::class);
Route::get('/admin/table/post/search', [AdminController::class, 'postSearch'])->name('search.post')->middleware(Isadmin::class);
Route::get('/admin/table/user/search/predictions', [AdminController::class, 'userSearchPredictions'])->name('user.predictions');
Route::get('/admin/table/post/search/predictions', [AdminController::class, 'postSearchPredictions'])->name('post.predictions');

// Post Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/create_post', function () {
        return view('posts.create_post');
    });
    Route::post('/insert_post', [PostController::class, 'storePost'])->name('post.store');

    // Delete & Edit Post
    Route::delete('/delete_post/{id}', [PostController::class, 'deletePost'])->name('post.destroy');
    Route::get('/edit_post/{id}', [PostController::class, 'editPost'])->name('post.edit');
    Route::put('/update_post/{id}', [PostController::class, 'updatePost'])->name('post.update');

    // Share Post to Feed
    Route::post('/posts/{post}/share-to-feed', [PostController::class, 'shareToFeed'])->name('post.shareToFeed');
    Route::get('/users/{user}/posts', [PostController::class, 'fetchUserPosts'])->name('user.posts');

    // User Posts
    Route::get('/users/{user}/posts', [PostController::class, 'fetchUserPosts'])->name('user.posts'); // สำหรับโพสต์ของผู้ใช้

    // ดึงโพสต์ทั้งหมด
    Route::get('/posts', [PostController::class, 'fetchPosts'])->name('posts.all');


    // Comment on Post
    Route::post('/post/{id}/comments', [CommentController::class, 'store'])->name('post.comment.store');
});

// Fetch posts for viewing
Route::get('/posts', [PostController::class, 'fetchPosts'])->name('posts.fetch');
Route::get('/post/{id}', [PostController::class, 'showFullPost'])->name('post.show');

// User Routes
Route::resource('users', UserController::class)->only(['show', 'edit', 'update'])->middleware('auth');
Route::get('profile', [UserController::class, 'profile'])->middleware('auth')->name('profile');

// Follow & Unfollow
Route::post('users/{user}/follow', [FollowerController::class, 'follow'])->middleware('auth')->name('users.follow');
Route::post('users/{user}/unfollow', [FollowerController::class, 'unfollow'])->middleware('auth')->name('users.unfollow');

// Search Routes
Route::get('/title/search', [PostController::class, 'titleSearch'])->name('title.search');
Route::get('/ingredients/search', [PostController::class, 'searchByIngredients'])->name('ingredients.search');

// Fetch more search results
Route::get('/title/fetch', [PostController::class, 'fetchTitle'])->name('fetch.search.title');
Route::get('/ingredients/fetch', [PostController::class, 'fetchIngredients'])->name('fetch.search.ingredients');

// Search predictions
Route::get('/title/predictions', [PostController::class, 'titleSearchPredictions'])->name('title.predictions');
Route::get('/ingredients/predictions', [PostController::class, 'ingredientsSearchPredictions'])->name('ingredients.predictions');

// Store ingredients in a session
Route::post('/store-ingredients', [PostController::class, 'storeIngredients'])->name('store.ingredients');
