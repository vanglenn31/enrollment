<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class ProfessorController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user()->load('profile.professor');
        $professor = optional($user->profile)->professor;

        $courses = collect();
        $classCount = 0;
        $studentCount = 0;
        $programCount = 0;

        if ($professor) {
            $courses = Course::with(['program.department'])
                ->withCount('studentEnrollments')
                ->where('professor_id', $professor->id)
                ->get();

            $classCount = $courses->count();
            $studentCount = $courses->sum('student_enrollments_count');
            $programCount = $courses->pluck('program_id')->unique()->count();
        }

        return view('professor.dashboard', compact(
            'professor',
            'courses',
            'classCount',
            'studentCount',
            'programCount'
        ));
    }

    public function courseList()
    {
        $user = auth()->user()->load('profile.professor');
        $professor = optional($user->profile)->professor;

        $courses = Course::with(['program.department'])
            ->withCount('studentEnrollments')
            ->when($professor, function ($query) use ($professor) {
                $query->where('professor_id', $professor->id);
            })
            ->get();

        return view('professor.course', compact('courses'));
    }
}
