<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GroupController as AdminGroupController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;

Route::group(
    [
        'middleware' => 'auth',
    ],
    static function () {
        Route::get('terms', [HomeController::class, 'terms'])->name('terms');
        Route::post('terms/accept', [HomeController::class, 'acceptTerms'])->name('terms.accept');

        Route::post('report', [HomeController::class, 'report'])->name('report');

        Route::group(
            [
                'middleware' => 'terms',
            ],
            static function () {
                Route::get('', [HomeController::class, 'index'])->name('index');
                Route::get('profile', [HomeController::class, 'profile'])->name('profile');

                Route::get('image/{imageId}', [ImageController::class, 'get'])->name('image');
                Route::get('image/{imageId}/{width}', [ImageController::class, 'getImageWithSource'])->name('image.source');

                Route::get('search', [PostController::class, 'search'])->name('search');

                Route::group(
                    [
                        'prefix' => 'profile',
                        'as' => 'profile.',
                    ],
                    static function () {
                        Route::get('settings', [ProfileController::class, 'settings'])->name('settings');

                        Route::post('edit', [ProfileController::class, 'edit'])->name('edit');
                    }
                );

                Route::group(
                    [
                        'prefix' => 'groups',
                        'as' => 'groups.',
                    ],
                    static function () {
                        Route::get('{groupId}',  [GroupController::class, 'posts'])->name('posts');
                    }
                );

                Route::group(
                    [
                        'prefix' => 'posts',
                        'as' => 'posts.',
                    ],
                    static function () {
                        Route::get('/offset/{offset}', [PostController::class, 'index'])->name('index');
                        Route::post('', [PostController::class, 'create'])->name('create');

                        Route::patch('{postId}/vote', [PostController::class, 'vote'])->name('vote');

                        Route::get('{postId}', [PostController::class, 'show'])->name('show');

                        Route::get('{postId}/image', [ImageController::class, 'getPost'])->name('image');
                        Route::get('{postId}/image/{width}', [ImageController::class, 'getPostWithSource'])->name('image.source');
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

                        Route::group(
                            [
                                'prefix' => 'groups',
                                'as' => 'groups.',
                                'middleware' => 'superadmin',
                            ],
                            static function () {
                                Route::get('', [AdminGroupController::class, 'index'])->name('index');
                            }
                        );

                        Route::group(
                            [
                                'prefix' => 'users',
                                'as' => 'users.',
                                'middleware' => 'superadmin',
                            ],
                            static function () {
                                Route::get('', [UserController::class, 'index'])->name('index');
                            }
                        );
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
