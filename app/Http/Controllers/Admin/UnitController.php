<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Course;
use App\Models\Level;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of units.
     */
    public function index(Request $request)
    {
        $query = Unit::with(['course', 'level'])
            ->withCount('questions');

        // Filter by level if provided
        if ($request->has('level')) {
            $query->where('level_id', $request->level);
        }

        // Filter by course if provided
        if ($request->has('course')) {
            $query->where('course_id', $request->course);
        }

        $units = $query->latest()->paginate(15);
        $courses = Course::orderBy('title')->get();

        return view('admin.units.index', compact('units', 'courses'));
    }

    /**
     * Show the form for creating a new unit.
     */
    public function create(Request $request)
    {
        $courses = Course::orderBy('title')->get();
        $courseId = $request->query('course');
        $levelId = $request->query('level');
        
        $selectedCourse = $courseId ? Course::find($courseId) : null;
        $selectedLevel = $levelId ? Level::find($levelId) : null;
        
        // If level is selected, get its course
        if ($selectedLevel && !$selectedCourse) {
            $selectedCourse = $selectedLevel->course;
        }
        
        // Get levels for the selected course
        $levels = $selectedCourse ? $selectedCourse->levels()->active()->ordered()->get() : collect();

        return view('admin.units.create', compact('courses', 'selectedCourse', 'selectedLevel', 'levels'));
    }

    /**
     * Store a newly created unit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'level_id' => 'required|exists:levels,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exam_month' => 'nullable|integer|min:1|max:12',
            'exam_year' => 'nullable|integer|min:2010|max:' . (date('Y') + 1),
        ]);

        // Auto-generate unit number (next available for this level)
        $nextUnitNumber = Unit::where('level_id', $validated['level_id'])
            ->max('unit_number') + 1;

        $validated['unit_number'] = $nextUnitNumber;
        $validated['order'] = $nextUnitNumber;

        Unit::create($validated);

        return redirect()->route('admin.units.index', ['level' => $validated['level_id']])
            ->with('success', 'Unit created successfully as Unit ' . $nextUnitNumber . '!');
    }

    /**
     * Display the specified unit.
     */
    public function show(Unit $unit)
    {
        $unit->load(['course', 'level', 'questions']);

        return view('admin.units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified unit.
     */
    public function edit(Unit $unit)
    {
        $courses = Course::orderBy('title')->get();
        $levels = $unit->course ? $unit->course->levels()->active()->ordered()->get() : collect();

        return view('admin.units.edit', compact('unit', 'courses', 'levels'));
    }

    /**
     * Update the specified unit.
     */
    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'level_id' => 'required|exists:levels,id',
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

    /**
     * Get units for a specific level (AJAX endpoint).
     */
    public function getUnitsForLevel(Level $level)
    {
        $units = $level->units()->orderBy('order')->get(['id', 'title', 'unit_number']);

        return response()->json($units);
    }

    /**
     * Get unit info including course_id and level_id (AJAX endpoint).
     */
    public function getUnitInfo(Unit $unit)
    {
        return response()->json([
            'id' => $unit->id,
            'title' => $unit->title,
            'course_id' => $unit->course_id,
            'level_id' => $unit->level_id,
        ]);
    }
}
