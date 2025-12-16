<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamPeriod;
use Illuminate\Http\Request;

class ExamPeriodController extends Controller
{
    /**
     * Display a listing of exam periods.
     */
    public function index()
    {
        $examPeriods = ExamPeriod::withCount('questions')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(20);

        return view('admin.exam-periods.index', compact('examPeriods'));
    }

    /**
     * Show the form for creating a new exam period.
     */
    public function create()
    {
        $months = ExamPeriod::MONTHS;
        $currentYear = date('Y');
        $years = range($currentYear - 2, $currentYear + 2);

        return view('admin.exam-periods.create', compact('months', 'years', 'currentYear'));
    }

    /**
     * Store a newly created exam period.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000|max:2100',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Check if period already exists
        $exists = ExamPeriod::where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['month' => 'An exam period for this month and year already exists.'])->withInput();
        }

        $validated['is_active'] = $request->has('is_active');

        ExamPeriod::create($validated);

        return redirect()->route('admin.exam-periods.index')
            ->with('success', 'Exam period created successfully!');
    }

    /**
     * Show the form for editing the specified exam period.
     */
    public function edit(ExamPeriod $examPeriod)
    {
        $months = ExamPeriod::MONTHS;
        $currentYear = date('Y');
        $years = range($currentYear - 5, $currentYear + 5);

        return view('admin.exam-periods.edit', compact('examPeriod', 'months', 'years'));
    }

    /**
     * Update the specified exam period.
     */
    public function update(Request $request, ExamPeriod $examPeriod)
    {
        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000|max:2100',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Check if period already exists (excluding current)
        $exists = ExamPeriod::where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->where('id', '!=', $examPeriod->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['month' => 'An exam period for this month and year already exists.'])->withInput();
        }

        $validated['is_active'] = $request->has('is_active');

        $examPeriod->update($validated);

        return redirect()->route('admin.exam-periods.index')
            ->with('success', 'Exam period updated successfully!');
    }

    /**
     * Remove the specified exam period.
     */
    public function destroy(ExamPeriod $examPeriod)
    {
        // Check if period has questions
        if ($examPeriod->questions()->count() > 0) {
            return back()->with('error', 'Cannot delete exam period that has questions assigned to it. Please reassign or delete the questions first.');
        }

        $examPeriod->delete();

        return redirect()->route('admin.exam-periods.index')
            ->with('success', 'Exam period deleted successfully!');
    }
}
