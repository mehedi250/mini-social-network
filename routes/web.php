<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile-settings', [ProfileController::class, 'edit'])->name('profile-settings.edit');
    Route::patch('/profile-settings', [ProfileController::class, 'update'])->name('profile-settings.update');
    Route::delete('/profile-settings', [ProfileController::class, 'destroy'])->name('profile-settings.destroy');

    Route::get('/profile/view/{userId?}', [UserProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');
});


Route::middleware('auth')->group(function () {
    Route::get('/feed', [PostController::class, 'index'])->name('feed');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    Route::post('/posts/{postId}/like', [PostLikeController::class, 'toggleLike'])->name('posts.like');

    Route::post('/posts/{postId}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
    Route::get('/posts/{postId}/comments', [CommentController::class, 'index'])->name('posts.comments.index');

});


require __DIR__.'/auth.php';
