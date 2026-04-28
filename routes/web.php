<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
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



// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');\

Route::prefix('student')
    ->name('student.')
    ->middleware(['auth', 'verified'])
    ->group(function () {

        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/course', [StudentController::class, 'course'])->name('course');
        Route::get('/enrollment', [StudentController::class, 'enrollment'])->name('enrollment');
        Route::get('/payment', [StudentController::class, 'payment'])->name('payment');

    });

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified'])
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/course', [AdminController::class, 'course'])->name('course');
        Route::get('/enrollment', [AdminController::class, 'enrollment'])->name('enrollment');
        Route::get('/payment', [AdminController::class, 'payment'])->name('payment');

    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    $user = auth()->user()->load('role');
    return match ($user->role->role) {
        'student' => redirect()->route('student.dashboard'),
        'admin' => redirect()->route('admin.dashboard'),
        'registrar' => redirect()->route('registrar.dashboard'),
        'teller' => redirect()->route('teller.dashboard'),
        default => redirect()->route('dashboard'),
    };
})->middleware('auth')->name('dashboard');





require __DIR__.'/auth.php';
