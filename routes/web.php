<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ForumController as AdminForumController;
use App\Http\Controllers\Admin\ThreadController as AdminThreadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Forum routes
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/search', action: [ForumController::class, 'search'])->name('forum.search');
Route::get('/forum/create', [ForumController::class, 'create'])->name('forum.create');
Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');
Route::get('/forum/{id}', [ForumController::class, 'show'])->name('forum.show');
Route::post('/comment/{id}/like', [ForumController::class, 'toggleCommentLike'])
    ->name('forum.comment.like');
Route::post('/forum/{id}/like', [ForumController::class, 'toggleLike'])->name('forum.togglelike');
Route::post('/forum/{id}/comment', [ForumController::class, 'storeComment'])->name('forum.comment');

// Authentication routes (simplified for this example)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Notification routes
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::get('/articles/my-articles', [ArticleController::class, 'myArticles'])->name('articles.myarticles');
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // User management
    Route::resource('users', UserController::class);
    
    // Forum Categories management
    Route::resource('forum', AdminForumController::class)->parameters([
    'forum' => 'category']);
    
    // Thread management
    Route::resource('threads', AdminThreadController::class);

    Route::get('/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/send', [App\Http\Controllers\Admin\NotificationController::class, 'send'])->name('notifications.send');

    // Add these Admin Article Routes
    Route::resource('articles', AdminArticleController::class);
    Route::patch('/articles/{article}/status', [AdminArticleController::class, 'updateStatus'])->name('articles.updateStatus');
});

// Password Reset Routes
Route::get('/password/reset', [App\Http\Controllers\PasswordResetController::class, 'showForgotPasswordForm'])
    ->name('password.request');
Route::post('/password/email', [App\Http\Controllers\PasswordResetController::class, 'sendPasswordResetRequest'])
    ->name('password.email');

// Admin Password Reset Management Routes
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    // Add this inside your existing admin route group
    Route::resource('password-resets', App\Http\Controllers\Admin\PasswordResetController::class)
        ->only(['index', 'edit', 'update']);
    Route::post('users/{user}/reset-password', [App\Http\Controllers\Admin\PasswordResetController::class, 'resetUserPassword'])
        ->name('users.reset-password');
});

// Public article routes
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/search', [ArticleController::class, 'search'])->name('articles.search');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');