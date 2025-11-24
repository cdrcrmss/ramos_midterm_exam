<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\RetweetController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

// Home route redirects to tweets if authenticated, login if not
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('tweets.index');
    }
    return redirect()->route('login');
})->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // Tweet routes
    Route::get('/tweets', [TweetController::class, 'index'])->name('tweets.index');
    Route::post('/tweets', [TweetController::class, 'store'])->name('tweets.store');
    Route::get('/tweets/{tweet}', [TweetController::class, 'show'])->name('tweets.show');
    Route::get('/tweets/{tweet}/edit', [TweetController::class, 'edit'])->name('tweets.edit');
    Route::put('/tweets/{tweet}', [TweetController::class, 'update'])->name('tweets.update');
    Route::delete('/tweets/{tweet}', [TweetController::class, 'destroy'])->name('tweets.destroy');
    
    // Tweet likes and retweets viewers
    Route::get('/tweets/{tweet}/likes', [TweetController::class, 'likes'])->name('tweets.likes');
    Route::get('/tweets/{tweet}/retweets', [TweetController::class, 'retweets'])->name('tweets.retweets');
    
    // Like routes
    Route::post('/tweets/{tweet}/like', [LikeController::class, 'toggle'])->name('likes.toggle');
    
    // Retweet routes
    Route::post('/tweets/{tweet}/retweet', [RetweetController::class, 'toggle'])->name('retweets.toggle');
    Route::get('/tweets/{tweet}/retweet', [RetweetController::class, 'create'])->name('retweets.create');
    
    // Follow routes
    Route::post('/users/{user}/follow', [FollowController::class, 'toggle'])->name('follow.toggle');
    Route::get('/users/{user}/followers', [UserController::class, 'followers'])->name('users.followers');
    Route::get('/users/{user}/following', [UserController::class, 'following'])->name('users.following');
    Route::get('/users/{user}/retweets', [UserController::class, 'retweets'])->name('users.retweets');
    
    // User profile routes
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.profile');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
    
    // Message routes - DISABLED
    /*
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/unread/count', [MessageController::class, 'unreadCount'])->name('messages.unread.count');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.conversation');
    Route::post('/messages/{user}/send', [MessageController::class, 'store'])->name('messages.send');
    */
    
    // Search routes
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/search/users', [SearchController::class, 'users'])->name('search.users');
    Route::get('/search/tweets', [SearchController::class, 'tweets'])->name('search.tweets');
});

?>
