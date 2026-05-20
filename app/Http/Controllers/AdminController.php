<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Professor;
use App\Models\Roles;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Term;
use App\Models\User;
use App\Models\EnrollmentByDepartment;
use App\Models\StudentsByProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function dashboard(\Illuminate\Http\Request $request)
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

        $enrollmentByDepartment = EnrollmentByDepartment::orderByDesc('enrolled_students')->get();

        $studentsByProgram = StudentsByProgram::orderByDesc('student_count')
            ->paginate(5, ['*'], 'program_page')
            ->withQueryString();

        return view('admin.dashboard', compact(
            'studentCount',
            'newStudentsThisMonth',
            'pendingReviews',
            'activePrograms',
            'enrollmentByDepartment',
            'studentsByProgram',
        ));
    }

    public function generateReport()
    {
        $studentCount = User::whereHas('role', fn($q) => $q->where('role', 'student'))->count();

        $newStudentsThisMonth = User::whereHas('role', fn($q) => $q->where('role', 'student'))
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $pendingReviews = Student::where('status', 'unverified')->count();
        $activePrograms = Program::where('status', 'active')->count();

        $verifiedCount   = Student::where('is_verified', true)->where('is_withdrawn', false)->count();
        $unverifiedCount = Student::where('is_verified', false)->where('is_withdrawn', false)->count();
        $withdrawnCount  = Student::where('is_withdrawn', true)->count();

        $enrollmentByDepartment = EnrollmentByDepartment::orderByDesc('enrolled_students')->get();
        $studentsByProgram      = StudentsByProgram::orderByDesc('student_count')->get();
        $departments            = Department::withCount('programs')->orderBy('name')->get();

        $activeProfessors   = Professor::where('status', 'active')->count();
        $inactiveProfessors = Professor::where('status', 'inactive')->count();

        $pdf = Pdf::loadView('admin.report', compact(
            'studentCount',
            'newStudentsThisMonth',
            'pendingReviews',
            'activePrograms',
            'verifiedCount',
            'unverifiedCount',
            'withdrawnCount',
            'enrollmentByDepartment',
            'studentsByProgram',
            'departments',
            'activeProfessors',
            'inactiveProfessors',
        ))->setPaper('a4', 'portrait');

        return $pdf->download('admin-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function students(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

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
            ->when($status === 'verified',   fn($q) => $q->where('is_verified', true)->where('is_withdrawn', false))
            ->when($status === 'unverified', fn($q) => $q->where('is_verified', false)->where('is_withdrawn', false))
            ->when($status === 'withdrawn',  fn($q) => $q->where('is_withdrawn', true))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalCount      = Student::count();
        $verifiedCount   = Student::where('is_verified', true)->where('is_withdrawn', false)->count();
        $unverifiedCount = Student::where('is_verified', false)->where('is_withdrawn', false)->count();
        $withdrawnCount  = Student::where('is_withdrawn', true)->count();

        return view('admin.students', compact('students', 'search', 'status', 'totalCount', 'verifiedCount', 'unverifiedCount', 'withdrawnCount'));
    }

    public function payment(Request $request)
    {
        return $this->payments($request);
    }

    public function professors(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $professors = Professor::with(['profile', 'department'])
            ->when($search, function ($query, $search) {
                $query->where('professor_number', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%");
                    });
            })
            ->when($status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalActive   = Professor::where('status', 'active')->count();
        $totalInactive = Professor::where('status', 'inactive')->count();

        return view('admin.professor', compact('professors', 'search', 'status', 'totalActive', 'totalInactive'));
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
                    $q->whereHas('studentEnrollment.student.profile', fn($q) =>
                            $q->where('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%"))
                      ->orWhereHas('studentEnrollment.student', fn($q) =>
                            $q->where('student_number', 'like', "%{$search}%"))
                      ->orWhereHas('studentEnrollment.course', fn($q) =>
                            $q->where('course_code', 'like', "%{$search}%")
                              ->orWhere('course_name', 'like', "%{$search}%"))
                      ->orWhere('payment_method', 'like', "%{$search}%")
                      ->orWhere('payment_status', 'like', "%{$search}%");
                });
            })
            ->latest('payment_date')
            ->paginate(10)
            ->withQueryString();

        return view('admin.payment', compact('payments', 'search'));
    }

    public function createProfessor()
    {
        $departments = Department::all();
        return view('admin.create-personnel', [
            'roleName'    => 'professor',
            'displayName' => 'Professor',
            'submitRoute' => route('admin.professors.store'),
            'departments' => $departments,
        ]);
    }

    public function storeProfessor(Request $request)
    {
        return $this->storePersonnel($request, 'professor');
    }

    public function editProfessor(User $professor)
    {
        $departments = Department::all();
        return view('admin.edit-personnel', [
            'roleName'    => 'professor',
            'displayName' => 'Professor',
            'submitRoute' => route('admin.professors.update', $professor),
            'personnel'   => $professor,
            'departments' => $departments,
        ]);
    }

    public function updateProfessor(Request $request, User $professor)
    {
        return $this->updatePersonnel($request, $professor, 'professor');
    }

    public function deactivateProfessor(User $professor)
    {
        optional($professor->profile?->professor)->update(['status' => 'inactive']);
        return back()->with('success', 'Professor deactivated.');
    }

    public function activateProfessor(User $professor)
    {
        optional($professor->profile?->professor)->update(['status' => 'active']);
        return back()->with('success', 'Professor activated.');
    }

    public function verifyStudent(Request $request, Student $student)
    {
        if (empty($student->student_number)) {
            $lastNumber = Student::whereNotNull('student_number')
                ->selectRaw('MAX(CAST(student_number AS UNSIGNED)) as max_number')
                ->value('max_number');
            $student->student_number = (string) ($lastNumber ? $lastNumber + 1 : 1);
        }

        $student->is_verified = true;
        $student->save();

        return redirect()->route('admin.students')->with('success', 'Student verified successfully and student number assigned.');
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
            'first_name'    => 'required|string|max:255',
            'middle_name'   => 'nullable|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'sex'           => 'required|in:male,female',
            'birthdate'     => 'required|date',
            'phone_number'  => 'required|string|max:20',
            'program'       => 'required|exists:programs,id',
            'house_number'  => 'nullable|string|max:255',
            'street'        => 'nullable|string|max:255',
            'barangay'      => 'required|string|max:255',
            'city'          => 'required|string|max:255',
            'province'      => 'required|string|max:255',
            'postal_code'   => 'required|string|max:10',
            'school_name'   => 'required|string|max:255',
            'year_graduated'=> 'required|integer|min:1900|max:' . (date('Y') + 1),
        ]);

        $student->update(['program' => $validated['program']]);
        $student->profile->update([
            'first_name'   => $validated['first_name'],
            'middle_name'  => $validated['middle_name'],
            'last_name'    => $validated['last_name'],
            'email'        => $validated['email'],
            'sex'          => $validated['sex'],
            'birthdate'    => $validated['birthdate'],
            'phone_number' => $validated['phone_number'],
        ]);
        $student->profile->address->update([
            'house_number' => $validated['house_number'],
            'street'       => $validated['street'],
            'barangay'     => $validated['barangay'],
            'city'         => $validated['city'],
            'province'     => $validated['province'],
            'postal_code'  => $validated['postal_code'],
        ]);
        $student->educationalBackground()->first()->update([
            'school'           => $validated['school_name'],
            'grad_date'        => $validated['year_graduated'] . '-01-01',
            'strand_or_course' => 'N/A',
        ]);

        return redirect()->route('admin.students')->with('success', 'Student updated successfully.');
    }

    protected function updatePersonnel(Request $request, User $personnel, string $roleName)
    {
        $existingRecord = optional($personnel->profile)->{$roleName};
        $numberColumn   = $roleName . '_number';
        $uniqueRule     = $existingRecord
            ? "unique:professors,{$numberColumn},{$existingRecord->id},id"
            : "unique:professors,{$numberColumn}";

        $validated = $request->validate([
            'first_name'       => ['required', 'string', 'max:100'],
            'middle_name'      => ['nullable', 'string', 'max:100'],
            'last_name'        => ['required', 'string', 'max:100'],
            'suffix'           => ['nullable', 'string', 'max:10'],
            'sex'              => ['required', 'string', 'max:20'],
            'birthdate'        => ['required', 'date'],
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $personnel->id],
            'phone_number'     => ['required', 'string', 'max:20'],
            'personnel_number' => ['required', 'string', 'max:100', $uniqueRule],
            'password'         => ['nullable', 'confirmed', Password::defaults()],
            'department_id'    => ['required', 'exists:departments,id'],
            'specialization'   => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $personnel, $numberColumn) {
            $personnel->update(['email' => $validated['email']]);
            if (! empty($validated['password'])) {
                $personnel->update(['password' => Hash::make($validated['password'])]);
            }
            $personnel->profile()->update([
                'first_name'   => $validated['first_name'],
                'middle_name'  => $validated['middle_name'],
                'last_name'    => $validated['last_name'],
                'suffix'       => $validated['suffix'],
                'sex'          => $validated['sex'],
                'birthdate'    => $validated['birthdate'],
                'phone_number' => $validated['phone_number'],
            ]);
            $personnel->profile->professor()->updateOrCreate(
                ['profile_id' => $personnel->profile->id],
                [
                    $numberColumn    => $validated['personnel_number'],
                    'department_id'  => $validated['department_id'],
                    'specialization' => $validated['specialization'] ?? null,
                ]
            );
        });

        return redirect()->route('admin.professors')->with('success', 'Professor updated successfully.');
    }

    protected function storePersonnel(Request $request, string $roleName)
    {
        $role = Roles::firstOrCreate(['role' => $roleName]);

        $validated = $request->validate([
            'first_name'     => ['required', 'string', 'max:100'],
            'middle_name'    => ['nullable', 'string', 'max:100'],
            'last_name'      => ['required', 'string', 'max:100'],
            'suffix'         => ['nullable', 'string', 'max:10'],
            'sex'            => ['required', 'string', 'max:20'],
            'birthdate'      => ['required', 'date'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone_number'   => ['required', 'string', 'max:20'],
            'password'       => ['required', 'confirmed', Password::defaults()],
            'department_id'  => ['required', 'exists:departments,id'],
            'specialization' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $role) {
            $user = User::create([
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id'  => optional($role)->id,
            ]);
            $profile = $user->profile()->create([
                'first_name'   => $validated['first_name'],
                'middle_name'  => $validated['middle_name'],
                'last_name'    => $validated['last_name'],
                'suffix'       => $validated['suffix'],
                'sex'          => $validated['sex'],
                'birthdate'    => $validated['birthdate'],
                'phone_number' => $validated['phone_number'],
            ]);
            $professor = $profile->professor()->create([
                'profile_id'     => $profile->id,
                'department_id'  => $validated['department_id'],
                'specialization' => $validated['specialization'] ?? null,
                'status'         => 'active',
            ]);
            $professor->professor_number = 'P' . $professor->id;
            $professor->save();
        });

        return redirect()->route('admin.professors')->with('success', 'Professor created successfully.');
    }
}