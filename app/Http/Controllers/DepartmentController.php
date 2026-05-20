<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function department(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status'); // '', 'active', or 'inactive'

        $query = Department::withCount('programs')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });

        // Counts (respects search but not the status filter)
        $baseQuery    = Department::when($search, fn($q) => $q->where('name', 'like', "%{$search}%"));
        $totalCount   = (clone $baseQuery)->count();
        $activeCount  = (clone $baseQuery)->where('status', 'active')->count();
        $inactiveCount = (clone $baseQuery)->where('status', 'inactive')->count();

        // Apply status filter
        if ($status === 'active' || $status === 'inactive') {
            $query->where('status', $status);
        }

        $departments = $query->latest()->paginate(10)->withQueryString();

        return view('admin.department', compact(
            'departments',
            'search',
            'status',
            'totalCount',
            'activeCount',
            'inactiveCount'
        ));
    }

    public function editDepartment(Department $department)
    {
        return view('admin.edit-department', compact('department'));
    }

    public function updateDepartment(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $department->update($validated);

        return redirect()->route('admin.department')->with('success', 'Department updated successfully.');
    }

    public function createDepartment()
    {
        return view('admin.create-department');
    }

    public function storeDepartment(Request $request)
    {
        if (Department::where('name', $request->name)->exists()) {
            return back()->withInput()->withErrors(['name' => 'A department with this name already exists.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Department::create(['name' => $request->name]);

        return redirect()->route('admin.department.department')->with('success', 'Department added successfully.');
    }

    public function deactivateDepartment(Department $department)
    {
        $department->update(['status' => 'inactive']);
        return back()->with('success', 'Department deactivated.');
    }

    public function activateDepartment(Department $department)
    {
        $department->update(['status' => 'active']);
        return back()->with('success', 'Department activated.');
    }
}