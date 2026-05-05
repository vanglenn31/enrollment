<?php

namespace App\Http\Controllers;
use App\Models\Department;
use App\Models\Program;

use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function programs(Request $request)
{
    $search = $request->input('search');

    $programs = Program::with('department')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhereHas('department', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        })
        ->latest()
        ->paginate(10)   // ✅ FIXED
        ->withQueryString();

    return view('admin.programs.programt', compact('programs', 'search'));
}

    public function editProgram(Program $program)
    {
        $departments = Department::where('status', 'active')->get();

        return view('admin.programs.edit-program', compact('program', 'departments'));
    }

    public function updateProgram(Request $request, Program $program)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:programs,code,' . $program->id,
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        $program->update($validated);

        return redirect()->route('admin.programs.programs')->with('success', 'Program updated successfully.');
    }

    public function createProgram()
    {
        $departments = Department::all();

        return view('admin.programs.create-program', compact('departments'));
    }

    public function storeProgram(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:programs,code',
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        Program::create($request->only(['code', 'name', 'department_id']));

        return redirect()->route('admin.programs.programs')->with('success', 'Program added successfully.');
    }

    public function deactivateProgram(Program $program)
    {
            $program->update([
                'status' => 'inactive'
            ]);

            return back()->with('success', 'Program deactivated.');
    }

    public function activateProgram(Program $program)
    {
        
        $program->update([
            'status' => 'active'
        ]);
        

        return back()->with('success', 'Program activated.');
    }
}
