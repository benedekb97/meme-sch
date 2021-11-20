<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PostController;

Route::group(
    [
        'middleware' => 'auth',
    ],
    static function () {
        Route::get('', [HomeController::class, 'index'])->name('index');
        Route::get('profile', [HomeController::class, 'profile'])->name('profile');

        Route::get('posts/{postId}/image', [ImageController::class, 'get'])->name('image');

        Route::group(
            [
                'prefix' => 'posts',
                'as' => 'posts.',
            ],
            static function () {
                Route::post('', [PostController::class, 'create'])->name('create');

                Route::patch('{postId}/vote', [PostController::class, 'vote'])->name('vote');
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
