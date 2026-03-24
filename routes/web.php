<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\Auth\LoginController;

// Admin
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;



Route::redirect('/', 'dashboard')
    ->name('root');
Route::redirect('/home', 'dashboard')
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('account', [AccountController::class, 'show'])->name('account');


    Route::prefix('admin/')->name('admin.')->group(function () {
        //Users
        Route::get('users', [UserController::class, 'index'])->name('users.index')->middleware('permission:users.view');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:users.create');
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:users.view');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:users.edit');

        // Roles
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:roles.view');
        Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:roles.create');
        Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show')->middleware('permission:roles.view');
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:roles.edit');
    });
});
