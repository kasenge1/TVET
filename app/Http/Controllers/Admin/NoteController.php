<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Stevebauman\Purify\Facades\Purify;

class NoteController extends Controller
{
    /**
     * Display a listing of notes.
     */
    public function index(Request $request)
    {
        $query = Note::with(['unit.course', 'creator']);

        // Filter by unit
        if ($request->has('unit')) {
            $query->where('unit_id', $request->unit);
        }

        // Filter by published status
        if ($request->has('published')) {
            $query->where('is_published', $request->published === '1');
        }

        $notes = $query->ordered()->paginate(15);
        $units = Unit::with('course')->orderBy('unit_number')->get();

        return view('admin.notes.index', compact('notes', 'units'));
    }

    /**
     * Show the form for creating a new note.
     */
    public function create(Request $request)
    {
        $units = Unit::with('course')->orderBy('unit_number')->get();
        $unitId = $request->query('unit');
        $selectedUnit = $unitId ? Unit::find($unitId) : null;

        return view('admin.notes.create', compact('units', 'selectedUnit'));
    }

    /**
     * Store a newly created note.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'nullable|integer|min:0',
            'is_published' => 'nullable|boolean',
        ]);

        // Sanitize HTML content
        $validated['content'] = Purify::clean($validated['content']);

        // Set defaults
        $validated['is_published'] = $request->boolean('is_published', true);
        $validated['created_by'] = Auth::id();

        Note::create($validated);

        return redirect()->route('admin.notes.index', ['unit' => $validated['unit_id']])
            ->with('success', 'Note created successfully!');
    }

    /**
     * Display the specified note.
     */
    public function show(Note $note)
    {
        $note->load(['unit.course', 'creator']);

        return view('admin.notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified note.
     */
    public function edit(Note $note)
    {
        $units = Unit::with('course')->orderBy('unit_number')->get();

        return view('admin.notes.edit', compact('note', 'units'));
    }

    /**
     * Update the specified note.
     */
    public function update(Request $request, Note $note)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'nullable|integer|min:0',
            'is_published' => 'nullable|boolean',
        ]);

        // Sanitize HTML content
        $validated['content'] = Purify::clean($validated['content']);

        // Set boolean value
        $validated['is_published'] = $request->boolean('is_published', true);

        $note->update($validated);

        return redirect()->route('admin.notes.show', $note)
            ->with('success', 'Note updated successfully!');
    }

    /**
     * Remove the specified note.
     */
    public function destroy(Note $note)
    {
        $unitId = $note->unit_id;
        $note->delete();

        return redirect()->route('admin.notes.index', ['unit' => $unitId])
            ->with('success', 'Note deleted successfully!');
    }

    /**
     * Upload image for Quill editor.
     */
    public function uploadQuillImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120', // 5MB max
        ]);

        $file = $request->file('image');
        $path = $file->store('note-images/' . date('Y/m'), 'public');

        return response()->json([
            'success' => true,
            'url' => Storage::disk('public')->url($path),
            'link' => Storage::disk('public')->url($path),
        ]);
    }
}
