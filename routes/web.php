<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\postcontroller;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Auth;

// Route สำหรับขอรีเซ็ตรหัสผ่าน
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Route สำหรับตั้งค่ารหัสผ่านใหม่
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// CommentNotification
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
});
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');


//home Route
Route::get('/', [postcontroller::class, 'showPost'])->name('home');

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
Route::get('/admin/table/user/search/predictions', [AdminController::class, 'userSearchPredictions'])->name('user.predictions')->middleware(Isadmin::class);
Route::get('/admin/table/post/search/predictions', [AdminController::class, 'postSearchPredictions'])->name('post.predictions')->middleware(Isadmin::class);
Route::get('/admin/reported-posts', [AdminController::class, 'viewReportedPosts'])->name('admin.viewReportedPosts')->middleware(Isadmin::class);

// Require login for creating and storing posts
Route::middleware(['auth'])->group(function () {
    Route::get('/create_post', function () {return view('posts/create_post');});
    Route::post('/insert_post', [postController::class, 'storePost'])->name('post.store');

    // Delete & Edit Post
    Route::delete('delete_post/{id}', [postController::class, 'deletePost'])->name('post.destroy');
    Route::get('edit_post/{id}', [postController::class, 'editPost'])->name('post.edit');
    Route::put('/update_post/{id}', [postController::class, 'updatePost'])->name('post.update');
    //admin delete
    Route::delete('delete_user/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete.user');

    // Share Post to Feed
    Route::post('/posts/{post}/share-to-feed', [postController::class, 'shareToFeed'])->name('post.shareToFeed');
    Route::get('/users/{user}/posts', [postController::class, 'fetchUserPosts'])->name('user.posts');
    // User Posts
    Route::get('/users/{user}/posts', [postController::class, 'fetchUserPosts'])->name('user.posts'); // สำหรับโพสต์ของผู้ใช้
    // ดึงโพสต์ทั้งหมด
    Route::get('/posts', [PostController::class, 'fetchPosts'])->name('posts.all');
    // Comment on Post
    Route::post('/post/{id}/comments', [CommentController::class, 'store'])->name('post.comment.store');
});

// post routes
Route::get('/posts', [postcontroller::class, 'fetchPosts']);
Route::get('/post/{id}', [postcontroller::class, 'showFullPost'])->name('post.show');
// require log in
Route::middleware(['auth'])->group(function () {
    Route::get('/following', [postController::class, 'followingPosts'])->name('following.posts');
    Route::get('/following/posts', [postcontroller::class, 'fentchFollowingPosts']);
});

// Rout Post Report
Route::post('/post/{id}/report', [PostController::class, 'report'])->name('post.report');

// Users Routes
Route::resource('users', UserController::class)->only(['show', 'edit', 'update'])->middleware('auth');
Route::get('profile', [UserController::class, 'profile'])->middleware('auth')->name('profile');
// Fetch more user posts
Route::get('/users/{user}/posts', [UserController::class, 'fetchUserPosts'])->middleware('auth');
//follow & unfollow
Route::post('users/{user}/follow', [FollowerController::class, 'follow'])->middleware('auth')->name('users.follow');
Route::post('users/{user}/unfollow', [FollowerController::class, 'unfollow'])->middleware('auth')->name('users.unfollow');

// Search Routes
Route::get('/title/search', [postController::class, 'titleSearch'])->name('title.search');
Route::get('/ingredients/search', [postController::class, 'searchByIngredients'])->name('ingredients.search');
// Fentch more search
Route::get('/title/fentch', [postController::class, 'fentchTitle'])->name('fentch.search.title');
Route::get('/ingredients/fentch', [postController::class, 'fentchIngredients'])->name('fentch.search.ingrredients');
// Search prediction route
Route::get('/title/predictions', [postController::class, 'titleSearchPredictions'])->name('title.predictions');
Route::get('/ingredients/predictions', [postController::class, 'ingredientsSearchPredictions'])->name('ingredients.predictions');

// store ingredients in a session
Route::post('/store-ingredients', [postcontroller::class, 'storeIngredients']);

//Comment
Route::post('/post/{id}/comments', [CommentController::class, 'store'])->name('post.comment.store');

//random recipe
Route::post('/random-recipe', [postController::class, 'randomRecipe'])->name('random.recipe');

//folling users route
Route::middleware(['auth'])->group(function () {
    Route::get('/following/users', [UserController::class, 'showAllFollowings'])->name('following.users.table');
    Route::get('/following/users/search', [UserController::class, 'userSearch'])->name('search.following');
    Route::get('/following/users/search/predictions', [UserController::class, 'userSearchPredictions'])->name('following.predictions');
});

//test
Route::get('/test/kimhun', function () {
    return view('shared.loading');
});
