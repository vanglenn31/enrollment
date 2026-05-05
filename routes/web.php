<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegistrarController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\TermController;
use Illuminate\Support\Facades\Route;

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


Route::prefix('student')
    ->name('student.')
    ->middleware(['auth', 'verified', 'active'])
    ->group(function () {

        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/course', [StudentController::class, 'course'])->name('course');
        Route::post('/course/enlist', [StudentController::class, 'enlistCourse'])->name('course.enlist');
        Route::get('/enrollment', [StudentController::class, 'enrollment'])->name('enrollment');
        Route::get('/payment', [StudentController::class, 'payment'])->name('payment');

    });

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'active', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/course', [AdminController::class, 'courses'])->name('course');
        Route::get('/courses/create', [AdminController::class, 'createCourse'])->name('courses.create');
        Route::post('/courses', [AdminController::class, 'storeCourse'])->name('courses.store');
        Route::get('/course/{course}/edit', [AdminController::class, 'editCourse'])->name('courses.edit');
        Route::put('/course/{course}', [AdminController::class, 'updateCourse'])->name('courses.update');
        Route::patch('/courses/{course}/deactivate', [AdminController::class, 'deactivateCourse'])->name('courses.deactivate');
        Route::patch('/courses/{course}/activate', [AdminController::class, 'activateCourse'])->name('courses.activate');

        Route::get('/enrollment', [AdminController::class, 'enrollment'])->name('enrollment');
        Route::get('/enrollment/{student}/assign', [AdminController::class, 'assignCoursesForm'])->name('enrollment.assign');
        Route::post('/enrollment/{student}/assign', [AdminController::class, 'storeEnrollment'])->name('enrollment.store');
        Route::get('/enrollment/{studentEnrollment}/edit', [AdminController::class, 'editEnrollment'])->name('enrollment.edit');
        Route::put('/enrollment/{studentEnrollment}', [AdminController::class, 'updateEnrollment'])->name('enrollment.update');
        Route::delete('/enrollment/{studentEnrollment}', [AdminController::class, 'removeEnrollment'])->name('enrollment.remove');
        Route::get('/payment', [AdminController::class, 'payment'])->name('payment');

        Route::get('/department', [AdminController::class, 'department'])->name('department');
        Route::get('/department/create', [AdminController::class, 'createDepartment'])->name('department.create');
        Route::post('/department', [AdminController::class, 'storeDepartment'])->name('department.store');
        Route::get('/department/{department}/edit', [AdminController::class, 'editDepartment'])->name('department.edit');
        Route::put('/department/{department}', [AdminController::class, 'updateDepartment'])->name('department.update');
        Route::patch('/department/{department}/deactivate', [AdminController::class, 'deactivateDepartment'])->name('department.deactivate');
        Route::patch('/department/{department}/activate', [AdminController::class, 'activateDepartment'])->name('department.activate');

        Route::get('/programs', [AdminController::class, 'programs'])->name('programs');
        Route::get('/programs/create', [AdminController::class, 'createProgram'])->name('programs.create');
        Route::post('/programs', [AdminController::class, 'storeProgram'])->name('programs.store');
        Route::get('/programs/{program}/edit', [AdminController::class, 'editProgram'])->name('programs.edit');
        Route::put('/programs/{program}', [AdminController::class, 'updateProgram'])->name('programs.update');
        Route::patch('/programs/{program}/deactivate', [AdminController::class, 'deactivateProgram'])->name('programs.deactivate');
        Route::patch('/programs/{program}/activate', [AdminController::class, 'activateProgram'])->name('programs.activate');

        Route::get('/students', [AdminController::class, 'students'])->name('students');
        Route::post('/students/{student}/verify', [AdminController::class, 'verifyStudent'])->name('students.verify');
        Route::get('/students/{student}/edit', [AdminController::class, 'editStudent'])->name('students.edit');
        Route::put('/students/{student}', [AdminController::class, 'updateStudent'])->name('students.update');
        Route::patch('/students/{student}/withdraw', [AdminController::class, 'withdrawStudent'])->name('students.withdraw');
        Route::patch('/students/{student}/reinstate', [AdminController::class, 'reinstateStudent'])->name('students.reinstate');

        Route::get('/professors', [AdminController::class, 'professors'])->name('professors');
        Route::get('/professors/create', [AdminController::class, 'createProfessor'])->name('professors.create');
        Route::post('/professors', [AdminController::class, 'storeProfessor'])->name('professors.store');
        Route::get('/professors/{professor}/edit', [AdminController::class, 'editProfessor'])->name('professors.edit');
        Route::put('/professors/{professor}', [AdminController::class, 'updateProfessor'])->name('professors.update');
        Route::patch('/professors/{professor}/deactivate', [AdminController::class, 'deactivateProfessor'])->name('professors.deactivate');
        Route::patch('/professors/{professor}/activate', [AdminController::class, 'activateProfessor'])->name('professors.activate');

        Route::get('/registrars', [AdminController::class, 'registrars'])->name('registrars');
        Route::get('/registrars/create', [AdminController::class, 'createRegistrar'])->name('registrars.create');
        Route::post('/registrars', [AdminController::class, 'storeRegistrar'])->name('registrars.store');
        Route::get('/registrars/{registrar}/edit', [AdminController::class, 'editRegistrar'])->name('registrars.edit');
        Route::put('/registrars/{registrar}', [AdminController::class, 'updateRegistrar'])->name('registrars.update');
        Route::patch('/registrars/{registrar}/deactivate', [AdminController::class, 'deactivateRegistrar'])->name('registrars.deactivate');
        Route::patch('/registrars/{registrar}/activate', [AdminController::class, 'activateRegistrar'])->name('registrars.activate');

        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
        Route::get('/payments/create', [AdminController::class, 'createPayment'])->name('payments.create');
        Route::post('/payments', [AdminController::class, 'storePayment'])->name('payments.store');

        Route::get('/rooms', [AdminController::class, 'rooms'])->name('rooms');
        Route::get('/rooms/create', [AdminController::class, 'createRoom'])->name('rooms.create');
        Route::post('/rooms', [AdminController::class, 'storeRoom'])->name('rooms.store');
        Route::get('/rooms/{room}/edit', [AdminController::class, 'editRoom'])->name('rooms.edit');
        Route::put('/rooms/{room}', [AdminController::class, 'updateRoom'])->name('rooms.update');
        Route::delete('/rooms/{room}', [AdminController::class, 'destroyRoom'])->name('rooms.destroy');

        // ── TERMS ────────────────────────────────────────────────────────────
        Route::prefix('terms')->name('terms.')->group(function () {
            Route::get('/',                           [TermController::class, 'index'])            ->name('index');
            Route::get('/create',                     [TermController::class, 'create'])           ->name('create');
            Route::post('/',                          [TermController::class, 'store'])            ->name('store');
            Route::get('/{term}/edit',                [TermController::class, 'edit'])             ->name('edit');
            Route::put('/{term}',                     [TermController::class, 'update'])           ->name('update');
            Route::patch('/{term}/activate',          [TermController::class, 'activate'])         ->name('activate');
            Route::patch('/{term}/end',               [TermController::class, 'end'])              ->name('end');
            Route::patch('/{term}/toggle-enrollment', [TermController::class, 'toggleEnrollment']) ->name('toggleEnrollment');
            Route::delete('/{term}',                  [TermController::class, 'destroy'])          ->name('destroy');
        });
        // ─────────────────────────────────────────────────────────────────────

    });

Route::prefix('registrar')
    ->name('registrar.')
    ->middleware(['auth', 'verified', 'active', 'role:registrar'])
    ->group(function () {
        Route::get('/dashboard', [RegistrarController::class, 'dashboard'])->name('dashboard');
        Route::get('/department', [AdminController::class, 'department'])->name('department');
        Route::get('/programs', [AdminController::class, 'programs'])->name('programs');
        Route::get('/students', [AdminController::class, 'students'])->name('students');
        Route::get('/professors', [AdminController::class, 'professors'])->name('professors');
        Route::get('/registrars', [AdminController::class, 'registrars'])->name('registrars');
        Route::get('/course', [AdminController::class, 'courses'])->name('course');
        Route::get('/enrollment', [AdminController::class, 'enrollment'])->name('enrollment');
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    });

Route::prefix('professor')
    ->name('professor.')
    ->middleware(['auth', 'verified', 'active', 'role:professor'])
    ->group(function () {
        Route::get('/dashboard', [ProfessorController::class, 'dashboard'])->name('dashboard');
        Route::get('/course', [ProfessorController::class, 'courseList'])->name('course');
    });

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    $user = auth()->user()->load('role');
    return match ($user->role->role) {
        'student'  => redirect()->route('student.dashboard'),
        'admin'    => redirect()->route('admin.dashboard'),
        'registrar'=> redirect()->route('registrar.dashboard'),
        'professor'=> redirect()->route('professor.dashboard'),
        default    => redirect()->route('dashboard'),
    };
})->middleware(['auth', 'active'])->name('dashboard');

require __DIR__.'/auth.php';