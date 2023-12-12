<?php

use App\Enum\Can;
use App\Livewire\Auth;
use App\Livewire\{Admin, Welcome};
use Illuminate\Support\Facades\Route;

//region Flow
Route::get('login', Auth\Login::class)->name('login');
Route::get('/registration', Auth\Register::class)->name('auth.register');
Route::get('/logout', Auth\Logout::class)->name('auth.logout');
Route::get('/email-verification', Auth\EmailValidation::class)->middleware('auth')->name('auth.email-verification');
Route::get('password/recovery', Auth\Password\Recovery::class)->name('password.recovery');
Route::get('password/reset/{token}/{email?}', Auth\Password\Reset::class)->name('password.reset');
//endregion

//region Authenticated
Route::middleware(['auth', 'verified.user'])->group(function () {
    Route::get('/', Welcome::class)->name('welcome');

    //region Admin
    Route::prefix('/admin')->middleware('can:' . Can::BE_AN_ADMIN->value)->group(function () {
        Route::get('/', Admin\Dashboard::class)->name('admin.dashboard');
        Route::get('users', Admin\Users\Index::class)->name('admin.users');
    });
    //endregion
});
//endregion
