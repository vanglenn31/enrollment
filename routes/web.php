<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\StudentEnrollmentController;
use App\Http\Controllers\RegistrarController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\RoomController;
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
        
        Route::prefix('course')->name('course.')->group(function () {
    Route::get('/', [CourseController::class, 'courses'])->name('course');
    Route::get('/create', [CourseController::class, 'createCourse'])->name('create');
    Route::post('/', [CourseController::class, 'storeCourse'])->name('store');

    Route::get('/{course}', [CourseController::class, 'showCourse'])->name('show');       // ← BEFORE edit
    Route::get('/{course}/edit', [CourseController::class, 'editCourse'])->name('edit');
    Route::put('/{course}', [CourseController::class, 'updateCourse'])->name('update');
    Route::patch('/{course}/deactivate', [CourseController::class, 'deactivateCourse'])->name('deactivate');
    Route::patch('/{course}/activate', [CourseController::class, 'activateCourse'])->name('activate');
});

// ✅ OUTSIDE the course prefix — produces name: admin.enrolled-course.grade
Route::patch('/enrolled-courses/{enrolledCourse}/grade', [CourseController::class, 'updateGrade'])->name('enrolled-course.grade');
        
        Route::prefix('enrollment')->name('enrollment.')->group(function () {
            Route::get('/', [StudentEnrollmentController::class, 'enrollment'])->name('enroll');
            Route::get('/{student}/assign', [StudentEnrollmentController::class, 'assignCoursesForm'])->name('assign');
            Route::post('/{student}/assign', [StudentEnrollmentController::class, 'storeEnrollment'])->name('store');
            Route::get('/{studentEnrollment}/edit', [StudentEnrollmentController::class, 'editEnrollment'])->name('edit');
            Route::put('/{studentEnrollment}', [StudentEnrollmentController::class, 'updateEnrollment'])->name('update');
            Route::delete('/{studentEnrollment}', [StudentEnrollmentController::class, 'removeEnrollment'])->name('remove');
        });

        Route::get('/payment', [AdminController::class, 'payment'])->name('payment');
        
        Route::prefix('department')->name('department.')->group(function () {
            Route::get('/', [DepartmentController::class, 'department'])->name('department');
            Route::get('/create', [DepartmentController::class, 'createDepartment'])->name('create');
            Route::post('/', [DepartmentController::class, 'storeDepartment'])->name('store');
            Route::get('/{department}/edit', [DepartmentController::class, 'editDepartment'])->name('edit');
            Route::put('/{department}', [DepartmentController::class, 'updateDepartment'])->name('update');
            Route::patch('/{department}/deactivate', [DepartmentController::class, 'deactivateDepartment'])->name('deactivate');
            Route::patch('/{department}/activate', [DepartmentController::class, 'activateDepartment'])->name('activate');
        });
        
        Route::prefix('programs')->name('programs.')->group(function () {
            Route::get('/programs', [ProgramController::class, 'programs'])->name('programs');
            Route::get('/programs/create', [ProgramController::class, 'createProgram'])->name('create');
            Route::post('/programs', [ProgramController::class, 'storeProgram'])->name('store');
            Route::get('/programs/{program}/edit', [ProgramController::class, 'editProgram'])->name('edit');
            Route::put('/programs/{program}', [ProgramController::class, 'updateProgram'])->name('update');
            Route::patch('/programs/{program}/deactivate', [ProgramController::class, 'deactivateProgram'])->name('deactivate');
            Route::patch('/programs/{program}/activate', [ProgramController::class, 'activateProgram'])->name('activate');
        });
            
        

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

        Route::prefix('rooms')->name('rooms.')->group(function () {
            Route::get('/', [RoomController::class, 'rooms'])->name('index');
            Route::get('/create', [RoomController::class, 'createRoom'])->name('create');
            Route::post('/', [RoomController::class, 'storeRoom'])->name('store');
            Route::get('/{room}/edit', [RoomController::class, 'editRoom'])->name('edit');
            Route::put('/{room}', [RoomController::class, 'updateRoom'])->name('update');
            Route::delete('/{room}', [RoomController::class, 'destroyRoom'])->name('destroy');
        });
        

        Route::prefix('terms')->name('terms.')->group(function () {
            Route::get('/', [TermController::class, 'index'])->name('index');
            Route::get('/create', [TermController::class, 'create'])->name('create');
            Route::post('/', [TermController::class, 'store'])->name('store');
            Route::get('/{term}/edit', [TermController::class, 'edit'])->name('edit');
            Route::put('/{term}', [TermController::class, 'update'])->name('update');
            Route::patch('/{term}/activate', [TermController::class, 'activate'])->name('activate');
            Route::patch('/{term}/end', [TermController::class, 'end'])->name('end');
            Route::patch('/{term}/toggle-enrollment', [TermController::class, 'toggleEnrollment']) ->name('toggleEnrollment');
            Route::delete('/{term}', [TermController::class, 'destroy'])->name('destroy');
        });

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