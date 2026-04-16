<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserFiles\UserFileController;
use App\Http\Controllers\UserFiles\MediaController;
use App\Http\Controllers\UserFiles\FileEditorController;

// Auth
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AccountController;

// Admin
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Lookups\UserLookup;
use App\Http\Controllers\UserFiles\SignaturesController;

Route::redirect('', 'files');

Route::redirect('files', 'files')
    ->name('root');

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
    //Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('myaccount', [AccountController::class, 'index'])->name('myaccount');

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

    Route::prefix('files')->name('files.')->group(function () {
        Route::get('', [UserFileController::class, 'index'])->name('index');

        Route::get('{userFileId}/content/{fileName?}', [MediaController::class, 'showByUserFileId'])
            ->name('content');
        Route::get('{userFileId}/content/{fileName?}/download', [MediaController::class, 'downloadByUserFileId'])
            ->name('download');

        Route::get('{userFileId}', [UserFileController::class, 'show'])
            ->name('show');

        Route::get('{userFileId}/editor/pdf', [FileEditorController::class, 'pdf'])
            ->name('editor.pdf');

        Route::get('{userFileId}/signatures/create', [SignaturesController::class, 'create'])
            ->name('signatures.create');
        Route::get('{userFileId}/signatures/{requestId}/edit', [SignaturesController::class, 'edit'])
            ->name('signatures.edit');
        Route::get('{userFileId}/signatures/{requestId}', [SignaturesController::class, 'show'])
            ->name('signatures.show');

        Route::get('{userFileId}/signatures/{requestId}/sign/{signatoryId}', [SignaturesController::class, 'sign'])
            ->name('signatures.sign');
    });

    Route::get('mysignature', [MediaController::class, 'mySignature'])->name('mysignature');

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
