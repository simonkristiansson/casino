<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->prefix('games')->group(function () {
    Route::get('/', [\App\Http\Controllers\GameController::class, 'index'])->name('games.index');
    Route::post('/', [\App\Http\Controllers\GameController::class, 'startGame'])->name('game.start');


    Route::get('/memory', [\App\Http\Controllers\GameController::class, 'memory'])->name('game.memory');
    Route::post('/memory', [\App\Http\Controllers\GameController::class, 'memoryStart'])->name('game.memory.start');
    Route::post('/memory/complete', [\App\Http\Controllers\GameController::class, 'memoryComplete'])->name('game.memory.complete');

});

require __DIR__.'/auth.php';
