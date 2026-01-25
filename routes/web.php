<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Categories;
use App\Livewire\Expenses;
use App\Livewire\Credits;
use App\Livewire\MonthlyTargets;
use App\Livewire\Reports;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('categories', Categories::class)
    ->middleware(['auth', 'verified'])
    ->name('categories');

Route::get('expenses', Expenses::class)
    ->middleware(['auth', 'verified'])
    ->name('expenses');

Route::get('credits', Credits::class)
    ->middleware(['auth', 'verified'])
    ->name('credits');

Route::get('monthly-targets', MonthlyTargets::class)
    ->middleware(['auth', 'verified'])
    ->name('monthly-targets');

Route::get('reports', Reports::class)
    ->middleware(['auth', 'verified'])
    ->name('reports');

require __DIR__.'/settings.php';
