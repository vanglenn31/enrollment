<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Program;
use App\Models\Professor;
use App\Models\Registrar;
use App\Models\User;
use Illuminate\Http\Request;

class RegistrarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        $studentCount = User::whereHas('role', function ($query) {
            $query->where('role', 'student');
        })->count();

        $courseCount = Course::count();
        $departmentCount = Department::count();
        $programCount = Program::count();
        $professorCount = Professor::count();
        $registrarCount = Registrar::count();

        return view('registrar.dashboard', compact(
            'studentCount',
            'courseCount',
            'departmentCount',
            'programCount',
            'professorCount',
            'registrarCount'
        ));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Registrar $registrar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Registrar $registrar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Registrar $registrar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Registrar $registrar)
    {
        //
    }
}
