<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LevelController extends Controller
{
    /**
     * Display a listing of levels.
     */
    public function index()
    {
        $levels = Level::withCount('courses')->orderBy('order')->get();

        return view('admin.levels.index', compact('levels'));
    }

    /**
     * Show the form for creating a new level.
     */
    public function create()
    {
        return view('admin.levels.create');
    }

    /**
     * Store a newly created level.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:levels,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        // Auto-generate order based on highest existing order + 1
        $validated['order'] = Level::max('order') + 1;

        Level::create($validated);

        return redirect()->route('admin.levels.index')
            ->with('success', 'Level created successfully!');
    }

    /**
     * Show the form for editing the specified level.
     */
    public function edit(Level $level)
    {
        return view('admin.levels.edit', compact('level'));
    }

    /**
     * Update the specified level.
     */
    public function update(Request $request, Level $level)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:levels,name,' . $level->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $level->update($validated);

        return redirect()->route('admin.levels.index')
            ->with('success', 'Level updated successfully!');
    }

    /**
     * Remove the specified level.
     */
    public function destroy(Level $level)
    {
        // Check if level has courses
        if ($level->courses()->count() > 0) {
            return back()->with('error', 'Cannot delete level that has courses assigned to it.');
        }

        $level->delete();

        return redirect()->route('admin.levels.index')
            ->with('success', 'Level deleted successfully!');
    }
}
