<?php

namespace App\Http\Controllers;
use App\Models\ProgramView;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        $query = ProgramView::where('status', 'active');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        if ($request->department) {
            $query->where('department_name', $request->department);
        }

        $programs = $query->paginate(12)->withQueryString();

        // Distinct department list for the filter dropdown
        $departments = ProgramView::where('status', 'active')
            ->whereNotNull('department_name')
            ->distinct()
            ->orderBy('department_name')
            ->pluck('department_name');

        return view('landing.programs', compact('programs', 'departments'));
    }
}