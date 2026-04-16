<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::prefix('/')->group(function() {
    Route::get('/', function () {
    return view('index');
    })->name('index');

    Route::get('/programs', function () {
    return view('landing.programs');
    })->name('programs');

    Route::get('/admission', function () {
    return view('landing.admission');
    })->name('admission');

    Route::get('/FAQ', function () {
    return view('landing.FAQ');
    })->name('FAQ');
// });


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
