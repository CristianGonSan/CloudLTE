<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Documents\DocumentController;
use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\Auth\LoginController;

// Admin
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Documents\DocumentCategoryController;
use App\Http\Controllers\Lookups\DocumentCategoryLookup;
use App\Http\Controllers\Lookups\UserLookup;

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
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('', [DocumentController::class, 'index'])->name('index');
        Route::get('create', [DocumentController::class, 'create'])->name('create');
        Route::get('{user}', [DocumentController::class, 'show'])->name('show');
        Route::get('{user}/edit', [DocumentController::class, 'edit'])->name('edit');

        Route::get('browser', [DocumentController::class, 'browser'])->name('browser');
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
