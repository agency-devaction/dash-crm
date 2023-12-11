<?php

use App\Enum\Can;
use App\Livewire\Auth\{Login, Logout, Password, Register};
use App\Livewire\{Admin, Welcome};
use Illuminate\Support\Facades\Route;

//region Flow
Route::get('login', Login::class)->name('login');
Route::get('/registration', Register::class)->name('auth.register');
Route::get('/email-verification', fn () => 'email-verification')->name('auth.email-verification');
Route::get('/logout', Logout::class)->name('auth.logout');
Route::get('password/recovery', Password\Recovery::class)->name('password.recovery');
Route::get('password/reset/{token}/{email?}', Password\Reset::class)->name('password.reset');
//endregion

//region Authenticated
Route::middleware('auth')->group(function () {
    Route::get('/', Welcome::class)->name('welcome');

    //region Admin
    Route::prefix('/admin')->middleware('can:' . Can::BE_AN_ADMIN->value)->group(function () {
        Route::get('/', Admin\Dashboard::class)->name('admin.dashboard');
        Route::get('users', Admin\Users\Index::class)->name('admin.users');
    });
    //endregion
});
//endregion
