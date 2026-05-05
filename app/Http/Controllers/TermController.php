<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
 
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter'); // upcoming | active | ended

        $terms = Term::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('school_year', 'like', "%{$search}%")
                      ->orWhere('semester', 'like', "%{$search}%");
                });
            })
            ->when($filter, function ($query, $filter) {
                $query->where('status', $filter);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $activeTerm = Term::active()->first();

        return view('admin.terms.index', compact('terms', 'search', 'filter', 'activeTerm'));
    }

    public function create()
    {
        return view('admin.terms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_year' => ['required', 'string', 'max:20', 'regex:/^\d{4}-\d{4}$/'],
            'semester'    => ['required', 'in:1st,2nd,summer'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
        ], [
            'school_year.regex' => 'School year must follow the format YYYY-YYYY (e.g. 2025-2026).',
        ]);

        // Prevent duplicate semester within the same school year
        $exists = Term::where('school_year', $validated['school_year'])
            ->where('semester', $validated['semester'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['semester' => 'A term for this school year and semester already exists.']);
        }

        Term::create($validated + ['status' => 'upcoming']);

        return redirect()
            ->route('admin.terms.index')
            ->with('success', 'Term created successfully.');
    }


    public function edit(Term $term)
    {
        return view('admin.terms.edit', compact('term'));
    }

    public function update(Request $request, Term $term)
    {
        $validated = $request->validate([
            'school_year' => ['required', 'string', 'max:20', 'regex:/^\d{4}-\d{4}$/'],
            'semester'    => ['required', 'in:1st,2nd,summer'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
        ], [
            'school_year.regex' => 'School year must follow the format YYYY-YYYY (e.g. 2025-2026).',
        ]);

        // Prevent duplicates (exclude current term)
        $exists = Term::where('school_year', $validated['school_year'])
            ->where('semester', $validated['semester'])
            ->where('id', '!=', $term->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['semester' => 'A term for this school year and semester already exists.']);
        }

        $term->update($validated);

        return redirect()
            ->route('admin.terms.index')
            ->with('success', 'Term updated successfully.');
    }

    

    public function activate(Term $term)
    {
        if ($term->status === 'ended') {
            return back()->withErrors(['term' => 'An ended term cannot be re-activated.']);
        }

        $term->activate();

        return back()->with('success', "Term {$term->label} is now active.");
    }

   

    public function end(Term $term)
    {
        if ($term->status === 'ended') {
            return back()->withErrors(['term' => 'This term has already ended.']);
        }

        $term->update([
            'status' => 'ended',
            'is_enrollment_open' => false
        ]);

        return back()->with('success', "Term {$term->label} has been ended.");
    }

    public function toggleEnrollment(Term $term)
    {
        if ($term->status !== 'active') {
            return back()->withErrors(['term' => 'Enrollment can only be toggled for the active term.']);
        }

        $term->toggleEnrollment();

        $state = $term->fresh()->is_enrollment_open ? 'opened' : 'closed';

        return back()->with('success', "Enrollment has been {$state} for {$term->label}.");
    }

  
    public function destroy(Term $term)
    {
        if ($term->status !== 'upcoming') {
            return back()->withErrors(['term' => 'Only upcoming terms can be deleted.']);
        }

        $term->delete();

        return redirect()
            ->route('admin.terms.index')
            ->with('success', 'Term deleted successfully.');
    }
    
}