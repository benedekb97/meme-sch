<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;

Route::group(
    [
        'middleware' => 'auth',
    ],
    static function () {
        Route::get('', [HomeController::class, 'index'])->name('index');
        Route::get('profile', [HomeController::class, 'profile'])->name('profile');

        Route::group(
            [
                'prefix' => 'profile',
                'as' => 'profile.',
            ],
            static function () {
                Route::get('settings', [ProfileController::class, 'settings'])->name('settings');
            }
        );

        Route::get('posts/{postId}/image', [ImageController::class, 'get'])->name('image');

        Route::group(
            [
                'prefix' => 'posts',
                'as' => 'posts.',
            ],
            static function () {
                Route::post('', [PostController::class, 'create'])->name('create');

                Route::patch('{postId}/vote', [PostController::class, 'vote'])->name('vote');

                Route::get('{postId}', [PostController::class, 'show'])->name('show');
            }
        );

        Route::group(
            [
                'prefix' => 'comments',
                'as' => 'comments.',
            ],
            static function () {
                Route::post('', [CommentController::class, 'create'])->name('create');

                Route::patch('{commentId}/vote', [CommentController::class, 'vote'])->name('vote');
            }
        );

        Route::group(
            [
                'middleware' => 'admin',
                'prefix' => 'admin',
                'as' => 'admin.'
            ],
            static function () {
                Route::get('', [DashboardController::class, 'index'])->name('index');

                Route::get('posts', [DashboardController::class, 'posts'])->name('posts');
                Route::get('approvals', [DashboardController::class, 'approvals'])->name('approvals');

                Route::get('refused-posts', [DashboardController::class, 'refusedPosts'])->name('refused-posts');

                Route::group(
                    [
                        'prefix' => 'posts',
                        'as' => 'posts.',
                    ],
                    static function () {
                        Route::patch('{postId}/approve', [AdminPostController::class, 'approve'])->name('approve');
                        Route::patch('{postId}/restore', [AdminPostController::class, 'restore'])->name('restore');

                        Route::delete('{postId}', [AdminPostController::class, 'refuse'])->name('refuse');
                    }
                );
            }
        );
    }
);

Route::group(
    [
        'as' => 'auth.',
        'prefix' => 'auth',
    ],
    static function () {
        Route::get('login', [AuthenticationController::class, 'login'])->name('login');

        Route::get('redirect', [AuthenticationController::class, 'redirect'])->name('redirect');
        Route::get('callback', [AuthenticationController::class, 'callback'])->name('callback');

        Route::get('logout', [AuthenticationController::class, 'logout'])->name('logout')->middleware('auth');
    }
);
