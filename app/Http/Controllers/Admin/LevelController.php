<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LevelController extends Controller
{
    /**
     * Display a listing of levels.
     */
    public function index(Request $request)
    {
        $query = Level::with('course')->withCount('units');

        // Filter by course if provided
        if ($request->has('course')) {
            $query->where('course_id', $request->course);
        }

        $levels = $query->orderBy('course_id')->orderBy('order')->paginate(20);
        $courses = Course::orderBy('title')->get();

        return view('admin.levels.index', compact('levels', 'courses'));
    }

    /**
     * Show the form for creating a new level.
     */
    public function create(Request $request)
    {
        $courses = Course::orderBy('title')->get();
        $selectedCourse = $request->query('course') ? Course::find($request->query('course')) : null;

        return view('admin.levels.create', compact('courses', 'selectedCourse'));
    }

    /**
     * Store a newly created level.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Check unique name within course
        $exists = Level::where('course_id', $validated['course_id'])
            ->where('name', $validated['name'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'This level name already exists for this course.'])->withInput();
        }

        $course = Course::find($validated['course_id']);
        $validated['slug'] = $course->slug . '-' . Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        // Auto-generate order and level_number based on highest existing for this course + 1
        $nextNumber = Level::where('course_id', $validated['course_id'])->max('order') + 1;
        $validated['order'] = $nextNumber;
        $validated['level_number'] = (string) $nextNumber;

        Level::create($validated);

        return redirect()->route('admin.levels.index', ['course' => $validated['course_id']])
            ->with('success', 'Level created successfully!');
    }

    /**
     * Show the form for editing the specified level.
     */
    public function edit(Level $level)
    {
        $courses = Course::orderBy('title')->get();

        return view('admin.levels.edit', compact('level', 'courses'));
    }

    /**
     * Update the specified level.
     */
    public function update(Request $request, Level $level)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Check unique name within course (excluding current level)
        $exists = Level::where('course_id', $validated['course_id'])
            ->where('name', $validated['name'])
            ->where('id', '!=', $level->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'This level name already exists for this course.'])->withInput();
        }

        $course = Course::find($validated['course_id']);
        $validated['slug'] = $course->slug . '-' . Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        // Don't update level_number - it's system generated
        $level->update($validated);

        return redirect()->route('admin.levels.index', ['course' => $validated['course_id']])
            ->with('success', 'Level updated successfully!');
    }

    /**
     * Remove the specified level.
     */
    public function destroy(Level $level)
    {
        // Check if level has units
        if ($level->units()->count() > 0) {
            return back()->with('error', 'Cannot delete level that has units assigned to it. Please delete or move the units first.');
        }

        $courseId = $level->course_id;
        $level->delete();

        return redirect()->route('admin.levels.index', ['course' => $courseId])
            ->with('success', 'Level deleted successfully!');
    }

    /**
     * Get levels for a specific course (AJAX endpoint).
     */
    public function getLevelsForCourse(Course $course)
    {
        $levels = $course->levels()->active()->ordered()->get(['id', 'name', 'level_number']);

        return response()->json($levels);
    }
}
