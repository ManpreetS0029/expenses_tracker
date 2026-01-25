<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

use App\Livewire\Dashboard;

Route::get('dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

use App\Livewire\Categories;

Route::get('categories', Categories::class)
    ->middleware(['auth', 'verified'])
    ->name('categories');

use App\Livewire\Expenses;
Route::get('expenses', Expenses::class)
    ->middleware(['auth', 'verified'])
    ->name('expenses');

use App\Livewire\MonthlyTargets;
Route::get('monthly-targets', MonthlyTargets::class)
    ->middleware(['auth', 'verified'])
    ->name('monthly-targets');

use App\Livewire\Reports;
Route::get('reports', Reports::class)
    ->middleware(['auth', 'verified'])
    ->name('reports');

require __DIR__ . '/settings.php';
