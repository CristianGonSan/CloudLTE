<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserFiles\UserFileController;
use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\Auth\LoginController;

// Admin
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FileEditorsController;
use App\Http\Controllers\Lookups\UserLookup;
use App\Http\Controllers\MediaController;

Route::redirect('', 'dashboard')
    ->name('root');
Route::redirect('home', 'dashboard')
    ->name('home');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('disabled', fn() => view('auth.disabled'))->name('auth.disabled');



Route::middleware(['auth', 'check.user.active'])->group(function () {

    // Core
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('my-account', [AccountController::class, 'show'])->name('my-account');

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        //Users
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('', [UserController::class, 'index'])->name('index')->middleware('permission:users.view');
            Route::get('create', [UserController::class, 'create'])->name('create')->middleware('permission:users.create');
            Route::get('{user}', [UserController::class, 'show'])->name('show')->middleware('permission:users.view');
            Route::get('{user}/edit', [UserController::class, 'edit'])->name('edit')->middleware('permission:users.edit');
        });

        // Roles
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('', [RoleController::class, 'index'])->name('index')->middleware('permission:roles.view');
            Route::get('create', [RoleController::class, 'create'])->name('create')->middleware('permission:roles.create');
            Route::get('{role}', [RoleController::class, 'show'])->name('show')->middleware('permission:roles.view');
            Route::get('{role}/edit', [RoleController::class, 'edit'])->name('edit')->middleware('permission:roles.edit');
        });
    });

    //Documents
    Route::prefix('user-files')->name('user-files.')->group(function () {
        Route::get('', [UserFileController::class, 'index'])->name('index');
        Route::get('create', [UserFileController::class, 'create'])->name('create');
        Route::get('{user}', [UserFileController::class, 'show'])->name('show');
        Route::get('{user}/edit', [UserFileController::class, 'edit'])->name('edit');

        Route::get('browser', [UserFileController::class, 'browser'])->name('browser');
    });

    Route::prefix('files')->name('files.')->group(function () {
        Route::get('{userFileId}/content/{fileName?}', [MediaController::class, 'showByUserFileId'])
            ->name('content');
        Route::get('{userFileId}/content/{fileName?}/download', [MediaController::class, 'downloadByUserFileId'])
            ->name('download');

        Route::get('{userFileId}/editor/pdf', [FileEditorsController::class, 'pdf'])
            ->name('editor.pdf');
    });

    /*
    |--------------------------------------------------------------------------
    | Lookups (Select2)
    |--------------------------------------------------------------------------
    */
    Route::prefix('lookups')->name('lookups.')->group(function () {
        Route::get('users/select2', [UserLookup::class, 'select2'])
            ->name('users.select2');
    });
});
