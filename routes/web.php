<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserContoller;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//auth route for both 
Route::group(['middleware' => ['auth']], function() { 
    Route::get('/dashboard', 'App\Http\Controllers\DashboardController@index')->name('dashboard');
});

// for users
Route::group(['middleware' => ['auth', 'role:user']], function() { 
    Route::get('/dashboard/myprofile', 'App\Http\Controllers\DashboardController@myprofile')->name('dashboard.myprofile');
});

// for blogwriters
Route::group(['middleware' => ['auth', 'role:blogwriter']], function() { 
    Route::get('/dashboard/blogwriter', 'App\Http\Controllers\DashboardController@blogwriter')->name('dashboard.blogwriterDash');
});


// Ressource Post
// create post
Route::middleware(['permission:create-post|create-user'])->group(function(){
    Route::get('/posts/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/posts', [PostController::class, 'store'])->name('post.store');
    Route::get('/user/create', [UserContoller::class, 'create'])->name('user.create');
    Route::post('/users', [UserContoller::class, 'store'])->name('user.store');
});

// read post
Route::middleware(['permission:read-post|read-user'])->group(function(){
    Route::get('/posts', [PostController::class, 'index'])->name('post.index');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('post.show');
    Route::get('/users', [UserContoller::class, 'index'])->name('user.index');
    Route::get('/users/{user}', [UserContoller::class, 'show'])->name('user.show');
});

// update post
Route::middleware(['permission:update-post|update-user'])->group(function(){
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('post.update');
    Route::get('/users/{user}/edit', [UserContoller::class, 'edit'])->name('user.edit');
    Route::put('/users/{user}', [UserContoller::class, 'update'])->name('user.update');
});

// delete post
Route::middleware(['permission:delete-post|delete-user'])->group(function(){
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('post.destroy');
    Route::delete('/users/{user}', [UserContoller::class, 'destroy'])->name('user.destroy');
});

Route::get('/botApi', 'App\Http\Controllers\TelegramBotController@sendMessage');
Route::post('/send-message', 'App\Http\Controllers\TelegramBotController@storeMessage');
Route::get('/send-photo', 'App\Http\Controllers\TelegramBotController@sendPhoto');
Route::post('/store-photo', 'App\Http\Controllers\TelegramBotController@storePhoto');
Route::get('/updated-activity', 'App\Http\Controllers\TelegramBotController@updatedActivity');

require __DIR__.'/auth.php';
