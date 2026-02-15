<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MonthlyTargetController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('categories', [CategoryController::class, 'index'])->name('categories');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses');
    Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::put('expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    Route::get('credits', [CreditController::class, 'index'])->name('credits');
    Route::post('credits', [CreditController::class, 'store'])->name('credits.store');
    Route::put('credits/{credit}', [CreditController::class, 'update'])->name('credits.update');
    Route::delete('credits/{credit}', [CreditController::class, 'destroy'])->name('credits.destroy');

    Route::get('monthly-targets', [MonthlyTargetController::class, 'index'])->name('monthly-targets');
    Route::post('monthly-targets', [MonthlyTargetController::class, 'store'])->name('monthly-targets.store');
    Route::put('monthly-targets/{monthlyTarget}', [MonthlyTargetController::class, 'update'])->name('monthly-targets.update');
    Route::delete('monthly-targets/{monthlyTarget}', [MonthlyTargetController::class, 'destroy'])->name('monthly-targets.destroy');

    Route::get('reports', [ReportController::class, 'index'])->name('reports');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
});

require __DIR__.'/settings.php';
