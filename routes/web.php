<?php

use App\Livewire\Auth\{Login, Password\Recovery, Register};
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;

Route::get('login', Login::class)->name('login');
Route::get('/registration', Register::class)->name('auth.register');
Route::get('/logout', static fn () => Auth::logout())->name('auth.logout');
Route::get('password/recovery', Recovery::class)->name('password.recovery');
Route::get('password/reset', fn () => 'oi')->name('password.reset');

Route::middleware('auth')->group(function () {
    Route::get('/', Welcome::class)->name('dashboard');
});
