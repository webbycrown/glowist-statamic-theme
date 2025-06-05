<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AuthorFollowController;
use App\Http\Controllers\StoreLikesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialShareController;
use App\Http\Controllers\BlogApiController;
use App\Http\Controllers\CommentLikeController;
use App\Http\Controllers\SearchController;

Route::fallback(function () {
    return response()->view('statamic::404', [], 404);
});

// Store a new comment
Route::post('/comments/store', [CommentController::class, 'store']);

// Handle author login
Route::post('/author/login', [LoginController::class, 'login'])->name('author.login');

// Handle author logout
Route::post('/author/logout', [LoginController::class, 'logout'])->name('author.logout');

// Statamic routes for homepage and login page
Route::statamic('/', 'home')->name('home');
Route::statamic('/login', 'login')->name('login');

// Follow an author
Route::post('/authors/follow', [AuthorFollowController::class, 'follow'])->name('authors.follow');

// Unfollow an author
Route::post('/authors/unfollow', [AuthorFollowController::class, 'unfollow'])->name('authors.unfollow');

// Like a blog post (store)
Route::post('/store/like', [StoreLikesController::class, 'like'])->name('blog.like');

// Unlike a blog post (store)
Route::post('/store/unlike', [StoreLikesController::class, 'unlike'])->name('blog.unlike');

// Update user profile
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

// Store social share action (using GET, consider POST if modifying data)
Route::get('/social-share', [SocialShareController::class,'store']);

// Fetch list of blog posts
Route::get('/blog-posts', [BlogApiController::class, 'index']);

// Partial view for author follow buttons
Route::get('/partials/author-follow-buttons', [BlogApiController::class, 'authorsFollowView']);

// Partial view for social sharing options
Route::get('/partials/social-option', [BlogApiController::class, 'socialOptionView']);

// Like a specific comment by ID
Route::post('/comments/{id}/like', [CommentLikeController::class, 'like']);

// Unlike a specific comment by ID
Route::post('/comments/{id}/unlike', [CommentLikeController::class, 'unlike']);

// Delete a comment (expects comment ID in request)
Route::delete('/comments/delete', [CommentLikeController::class, 'delete']);

// Search comments or posts based on query parameter
Route::get('/comments/search', [SearchController::class, 'search']);