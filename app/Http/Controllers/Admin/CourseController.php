<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index()
    {
        $courses = Course::with('creator')
            ->withCount(['units', 'enrollments'])
            ->latest()
            ->paginate(15);

        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        return view('admin.courses.create');
    }

    /**
     * Store a newly created course.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:courses,code',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_published'] = false;

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            $validated['thumbnail_url'] = $path;
        }

        $course = Course::create($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully!');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $course->load(['units.questions', 'enrollments.user']);
        
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * Update the specified course.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:courses,code,' . $course->id,
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($course->thumbnail_url) {
                Storage::disk('public')->delete($course->thumbnail_url);
            }

            $path = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            $validated['thumbnail_url'] = $path;
        }

        $course->update($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified course.
     */
    public function destroy(Course $course)
    {
        // Delete thumbnail
        if ($course->thumbnail_url) {
            Storage::disk('public')->delete($course->thumbnail_url);
        }

        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully!');
    }

    /**
     * Publish a course.
     */
    public function publish(Course $course)
    {
        $course->update(['is_published' => true]);

        return back()->with('success', 'Course published successfully!');
    }

    /**
     * Unpublish a course.
     */
    public function unpublish(Course $course)
    {
        $course->update(['is_published' => false]);

        return back()->with('success', 'Course unpublished successfully!');
    }

    /**
     * Handle bulk actions on courses.
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:publish,unpublish,delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:courses,id',
        ]);

        $courses = Course::whereIn('id', $validated['ids']);
        $count = $courses->count();

        switch ($validated['action']) {
            case 'publish':
                $courses->update(['is_published' => true]);
                $message = "{$count} course(s) published successfully!";
                break;

            case 'unpublish':
                $courses->update(['is_published' => false]);
                $message = "{$count} course(s) unpublished successfully!";
                break;

            case 'delete':
                // Delete thumbnails first
                $coursesToDelete = $courses->get();
                foreach ($coursesToDelete as $course) {
                    if ($course->thumbnail_url) {
                        Storage::disk('public')->delete($course->thumbnail_url);
                    }
                }
                Course::whereIn('id', $validated['ids'])->delete();
                $message = "{$count} course(s) deleted successfully!";
                break;

            default:
                return back()->with('error', 'Invalid action.');
        }

        return redirect()->route('admin.courses.index')->with('success', $message);
    }
}
