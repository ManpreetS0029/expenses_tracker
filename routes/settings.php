<?php

use App\Http\Controllers\Settings\CurrencyController;
use App\Http\Controllers\Settings\DeleteUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', function () {
        return view('pages.settings.profile');
    })->name('profile.edit');

    Route::delete('/user', [DeleteUserController::class, 'destroy'])->name('user.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('settings/password', function () {
        return view('pages.settings.password');
    })->name('user-password.edit');

    Route::get('settings/currency', [CurrencyController::class, 'edit'])->name('currency.edit');
    Route::post('settings/currency', [CurrencyController::class, 'update'])->name('currency.update');
});
