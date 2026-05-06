<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Professor;
use App\Models\Registrar;
use App\Models\Roles;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Room;

class AdminController extends Controller
{
    public function dashboard()
    {
        $studentCount = User::whereHas('role', function ($query) {
            $query->where('role', 'student');
        })->count();

        $newStudentsThisMonth = User::whereHas('role', function ($query) {
            $query->where('role', 'student');
        })->whereMonth('created_at', now()->month)
          ->whereYear('created_at', now()->year)
          ->count();

        $pendingReviews = Student::where('status', 'unverified')->count();

        $activePrograms = Program::count();

        $studentsByProgram = Program::withCount('students')->get();

        return view('admin.dashboard', compact('studentCount', 'newStudentsThisMonth', 'pendingReviews', 'activePrograms', 'studentsByProgram'));
    }

    

    

    

    public function students(Request $request)
{
    $search = $request->input('search');

    $students = Student::with(['profile.user', 'programRelation'])
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                  ->orWhereHas('profile', function ($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('programRelation', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        })
        ->latest() // ✅ safer default sorting
        ->paginate(10) // 🔥 FIX: enables links()
        ->withQueryString();

    return view('admin.students', compact('students', 'search'));
}

    

    public function payment(Request $request)
    {
        return $this->payments($request);
    }

    public function professors(Request $request)
{
    $search = $request->input('search');

    $professors = Professor::with(['profile', 'department'])
        ->when($search, function ($query, $search) {
            $query->where('professor_number', 'like', "%{$search}%")
                ->orWhereHas('profile', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.professor', compact('professors', 'search'));
}

    public function registrars(Request $request)
{
    $search = $request->input('search');

    $registrars = User::whereHas('role', function ($query) {
            $query->where('role', 'registrar');
        })
        ->with('registrar', 'profile')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('registrar', function ($q) use ($search) {
                        $q->where('registrar_number', 'like', "%{$search}%");
                    })
                  ->orWhereHas('profile', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%");
                    })
                  ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.registrart', compact('registrars', 'search'));
}

    public function payments(Request $request)
{
    $search = $request->input('search');

    $payments = Payment::with([
            'studentEnrollment.student.profile',
            'studentEnrollment.course'
        ])
        ->when($search, function ($query, $search) {

            $query->where(function ($q) use ($search) {

                $q->whereHas('studentEnrollment.student.profile', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%");
                    })
                  ->orWhereHas('studentEnrollment.student', function ($q) use ($search) {
                        $q->where('student_number', 'like', "%{$search}%");
                    })
                  ->orWhereHas('studentEnrollment.course', function ($q) use ($search) {
                        $q->where('course_code', 'like', "%{$search}%")
                          ->orWhere('course_name', 'like', "%{$search}%");
                    })
                  ->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhere('payment_status', 'like', "%{$search}%");

            });

        })
        ->latest('payment_date')
        ->paginate(10)   // 🔥 FIXED
        ->withQueryString();

    return view('admin.payment', compact('payments', 'search'));
}

    public function createProfessor()
    {
        $departments = Department::all();

        return view('admin.create-personnel', [
            'roleName' => 'professor',
            'displayName' => 'Professor',
            'submitRoute' => route('admin.professors.store'),
            'departments' => $departments,
        ]);
    }

    public function storeProfessor(Request $request)
    {
        return $this->storePersonnel($request, 'professor');
    }

    public function createRegistrar()
    {
        return view('admin.create-personnel', [
            'roleName' => 'registrar',
            'displayName' => 'Registrar',
            'submitRoute' => route('admin.registrars.store'),
        ]);
    }

    public function storeRegistrar(Request $request)
    {
        return $this->storePersonnel($request, 'registrar');
    }

    public function editProfessor(User $professor)
    {
        $departments = Department::all();

        return view('admin.edit-personnel', [
            'roleName' => 'professor',
            'displayName' => 'Professor',
            'submitRoute' => route('admin.professors.update', $professor),
            'personnel' => $professor,
            'departments' => $departments,
        ]);
    }

    public function updateProfessor(Request $request, User $professor)
    {
        return $this->updatePersonnel($request, $professor, 'professor');
    }

    public function deactivateProfessor(Professor $professor)
{
    $professor->update([
        'status' => 'inactive'
    ]);

    return back()->with('success', 'Professor deactivated successfully.');
}

    public function activateProfessor(Professor $professor)
{
    $professor->update([
        'status' => 'active'
    ]);

    return back()->with('success', 'Professor activated successfully.');
}

    public function editRegistrar(User $registrar)
    {
        return view('admin.edit-personnel', [
            'roleName' => 'registrar',
            'displayName' => 'Registrar',
            'submitRoute' => route('admin.registrars.update', $registrar),
            'personnel' => $registrar,
        ]);
    }

    public function updateRegistrar(Request $request, User $registrar)
    {
        return $this->updatePersonnel($request, $registrar, 'registrar');
    }

    public function deactivateRegistrar(User $registrar)
    {
        $registrar->update(['is_active' => false]);

        return redirect()->route('admin.registrars')->with('success', 'Registrar deactivated successfully.');
    }

    public function activateRegistrar(User $registrar)
    {
        $registrar->update(['is_active' => true]);

        return redirect()->route('admin.registrars')->with('success', 'Registrar activated successfully.');
    }

    protected function updatePersonnel(Request $request, User $personnel, string $roleName)
    {
        $table = match ($roleName) {
            'professor' => 'professors',
            'registrar' => 'registrars',
        };

        $numberColumn = $roleName . '_number';
        if ($roleName === 'professor') {
            $existingRecord = optional($personnel->profile)->{$roleName};
        } else {
            $existingRecord = optional($personnel->{$roleName});
        }
        $uniqueRule = $existingRecord ? "unique:{$table},{$numberColumn},{$existingRecord->id},id" : "unique:{$table},{$numberColumn}";

        $rules = [
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'suffix' => ['nullable', 'string', 'max:10'],
            'sex' => ['required', 'string', 'max:20'],
            'birthdate' => ['required', 'date'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $personnel->id],
            'phone_number' => ['required', 'string', 'max:20'],
            'personnel_number' => ['required', 'string', 'max:100', $uniqueRule],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ];

        if ($roleName === 'professor') {
            $rules['department_id'] = ['required', 'exists:departments,id'];
            $rules['specialization'] = ['nullable', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated, $personnel, $roleName, $table, $numberColumn) {
            $personnel->update([
                'email' => $validated['email'],
            ]);

            if (! empty($validated['password'])) {
                $personnel->update(['password' => Hash::make($validated['password'])]);
            }

            if ($roleName === 'professor') {
                $personnel->profile()->update([
                    'first_name' => $validated['first_name'],
                    'middle_name' => $validated['middle_name'],
                    'last_name' => $validated['last_name'],
                    'suffix' => $validated['suffix'],
                    'sex' => $validated['sex'],
                    'birthdate' => $validated['birthdate'],
                    'phone_number' => $validated['phone_number'],
                ]);

                $profileRelation = $personnel->profile->{$roleName}();
                $profileRelation->updateOrCreate(
                    ['profile_id' => $personnel->profile->id],
                    array_merge([
                        $numberColumn => $validated['personnel_number'],
                    ], $roleName === 'professor' ? [
                        'department_id' => $validated['department_id'],
                        'specialization' => $validated['specialization'] ?? null,
                    ] : [])
                );
            } else {
                $personnel->profile()->update([
                    'first_name' => $validated['first_name'],
                    'middle_name' => $validated['middle_name'],
                    'last_name' => $validated['last_name'],
                    'suffix' => $validated['suffix'],
                    'sex' => $validated['sex'],
                    'birthdate' => $validated['birthdate'],
                    'phone_number' => $validated['phone_number'],
                ]);

                $userRelation = $personnel->{$roleName}();
                $userRelation->updateOrCreate(
                    ['user_id' => $personnel->id],
                    [
                        $numberColumn => $validated['personnel_number'],
                    ]
                );
            }
        });

        return redirect()->route('admin.' . $roleName . 's')->with('success', ucfirst($roleName) . ' updated successfully.');
    }

    protected function storePersonnel(Request $request, string $roleName)
{
    $role = Roles::firstOrCreate([
        'role' => $roleName,
    ]);

    $table = match ($roleName) {
        'professor' => 'professors',
        'registrar' => 'registrars',
    };

    $numberColumn = $roleName . '_number';

    $rules = [
        'first_name' => ['required', 'string', 'max:100'],
        'middle_name' => ['nullable', 'string', 'max:100'],
        'last_name' => ['required', 'string', 'max:100'],
        'suffix' => ['nullable', 'string', 'max:10'],
        'sex' => ['required', 'string', 'max:20'],
        'birthdate' => ['required', 'date'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        'phone_number' => ['required', 'string', 'max:20'],
        'password' => ['required', 'confirmed', Password::defaults()],
        // ❌ REMOVED personnel_number
    ];

    if ($roleName === 'professor') {
        $rules['department_id'] = ['required', 'exists:departments,id'];
        $rules['specialization'] = ['nullable', 'string', 'max:255'];
    }

    $validated = $request->validate($rules);

    DB::transaction(function () use ($validated, $role, $roleName, $numberColumn) {

        // CREATE USER
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => optional($role)->id,
        ]);

        // CREATE PROFILE
        $profile = $user->profile()->create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'suffix' => $validated['suffix'],
            'sex' => $validated['sex'],
            'birthdate' => $validated['birthdate'],
            'phone_number' => $validated['phone_number'],
        ]);

        if ($roleName === 'professor') {

            // ✅ CREATE PROFESSOR FIRST
            $professor = $profile->professor()->create([
                'profile_id' => $profile->id,
                'department_id' => $validated['department_id'],
                'specialization' => $validated['specialization'] ?? null,
                'status' => 'active',
            ]);

            // ✅ GENERATE P1, P2, P3...
            $professor->professor_number = 'P' . $professor->id;
            $professor->save();

        } else {

            // REGISTRAR (or other personnel)
            $personnel = $user->{$roleName}()->create([
                'user_id' => $user->id,
            ]);

            // Example: R1, R2, R3...
            $personnel->{$numberColumn} = strtoupper(substr($roleName, 0, 1)) . $personnel->id;
            $personnel->save();
        }
    });

    return redirect()
        ->route('admin.' . $roleName . 's')
        ->with('success', ucfirst($roleName) . ' created successfully.');
}

    public function createPayment()
    {
        return view('admin.create-payment');
    }

    public function storePayment(Request $request)
{
    $validated = $request->validate([
        'student_number' => 'required|string|exists:students,student_number',
        'amount' => 'required|numeric|min:0',
        'payment_date' => 'nullable|date',
        'payment_method' => 'nullable|in:cash,gcash,bank_transfer',
        'payment_status' => 'nullable|in:pending,partial,paid,cancelled',
    ]);

    $student = Student::where('student_number', $validated['student_number'])->firstOrFail();

    $studentEnrollment = $student->studentEnrollments()
        ->latest()
        ->first();

    if (! $studentEnrollment) {
        return back()
            ->withErrors([
                'student_number' => 'This student has no enrollment record yet.'
            ])
            ->withInput();
    }

    Payment::create([
        'student_enrollment_id' => $studentEnrollment->id,
        'amount' => $validated['amount'],
        'payment_date' => $validated['payment_date'] ?? now(), // safer default
        'payment_method' => $validated['payment_method'] ?? 'cash',
        'payment_status' => $validated['payment_status'] ?? 'pending',
    ]);

    return redirect()
        ->route('admin.payments')
        ->with('success', 'Payment record created successfully.');
}

    public function verifyStudent(Request $request, Student $student)
{
    if (empty($student->student_number)) {

        // Get the highest existing numeric student number
        $lastNumber = Student::whereNotNull('student_number')
            ->selectRaw('MAX(CAST(student_number AS UNSIGNED)) as max_number')
            ->value('max_number');

        // If no records yet, start at 1
        $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

        // Assign incremental number (you can keep prefix or remove it)
        $student->student_number = (string) $nextNumber;
    }

    $student->is_verified =  true;
    $student->save();

    return redirect()
        ->route('admin.students')
        ->with('success', 'Student verified successfully and student number assigned.');
}

    public function withdrawStudent(Student $student)
    {
        $student->profile->user->update(['is_active' => false]);
        $student->update(['is_withdrawn' => true, 'status' => 'withdrawn']);

        return redirect()->route('admin.students')->with('success', 'Student withdrawn successfully.');
    }

    public function reinstateStudent(Student $student)
    {
        $student->profile->user->update(['is_active' => true]);
        $student->update(['is_withdrawn' => false, 'status' => 'verified']);

        return redirect()->route('admin.students')->with('success', 'Student reinstated successfully.');
    }

    public function editStudent(Student $student)
    {
        $student->load(['profile.address', 'programRelation', 'educationalBackground']);

        return view('admin.edit-student', compact('student'));
    }

    public function updateStudent(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'sex' => 'required|in:male,female',
            'birthdate' => 'required|date',
            'phone_number' => 'required|string|max:20',
            'program' => 'required|exists:programs,id',
            'house_number' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'barangay' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'school_name' => 'required|string|max:255',
            'year_graduated' => 'required|integer|min:1900|max:' . (date('Y') + 1),
        ]);

        $student->update([
            'program' => $validated['program'],
        ]);

        $student->profile->update([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'sex' => $validated['sex'],
            'birthdate' => $validated['birthdate'],
            'phone_number' => $validated['phone_number'],
        ]);

        $student->profile->address->update([
            'house_number' => $validated['house_number'],
            'street' => $validated['street'],
            'barangay' => $validated['barangay'],
            'city' => $validated['city'],
            'province' => $validated['province'],
            'postal_code' => $validated['postal_code'],
        ]);

        $student->educationalBackground()->first()->update([
            'school' => $validated['school_name'],
            'grad_date' => $validated['year_graduated'] . '-01-01', // Assuming year only
            'strand_or_course' => 'N/A', // Not used in form
        ]);

        return redirect()->route('admin.students')->with('success', 'Student updated successfully.');
    }

    

    
}

