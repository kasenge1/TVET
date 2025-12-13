<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Course;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of units.
     */
    public function index()
    {
        $units = Unit::with('course')
            ->withCount('questions')
            ->latest()
            ->paginate(15);

        return view('admin.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new unit.
     */
    public function create(Request $request)
    {
        $courses = Course::orderBy('title')->get();
        $courseId = $request->query('course');
        $selectedCourse = $courseId ? Course::find($courseId) : null;

        return view('admin.units.create', compact('courses', 'selectedCourse'));
    }

    /**
     * Store a newly created unit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exam_month' => 'nullable|integer|min:1|max:12',
            'exam_year' => 'nullable|integer|min:2010|max:' . (date('Y') + 1),
        ]);

        // Auto-generate unit number (next available for this course)
        $nextUnitNumber = Unit::where('course_id', $validated['course_id'])
            ->max('unit_number') + 1;

        $validated['unit_number'] = $nextUnitNumber;
        $validated['order'] = $nextUnitNumber;

        Unit::create($validated);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit created successfully as Unit ' . $nextUnitNumber . '!');
    }

    /**
     * Display the specified unit.
     */
    public function show(Unit $unit)
    {
        $unit->load(['course', 'questions']);

        return view('admin.units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified unit.
     */
    public function edit(Unit $unit)
    {
        $courses = Course::orderBy('title')->get();

        return view('admin.units.edit', compact('unit', 'courses'));
    }

    /**
     * Update the specified unit.
     */
    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exam_month' => 'nullable|integer|min:1|max:12',
            'exam_year' => 'nullable|integer|min:2010|max:' . (date('Y') + 1),
        ]);

        $unit->update($validated);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit updated successfully!');
    }

    /**
     * Remove the specified unit.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit deleted successfully!');
    }
}
