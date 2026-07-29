@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Edit Note: ' . $note->title)
@section('page-actions')
    <a href="{{ route('admin.notes.show', $note) }}" class="btn-modern btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Note
    </a>
@endsection

@section('main')
<form action="{{ route('admin.notes.update', $note) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <div class="col-xl-8">
            <x-card title="Note Content">
                <div class="mb-4">
                    <label for="title" class="form-label fw-medium">Title <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('title') is-invalid @enderror"
                           id="title"
                           name="title"
                           value="{{ old('title', $note->title) }}"
                           placeholder="e.g., Key Concepts, Important Formulas"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Content <span class="text-danger">*</span></label>
                    <x-quill-editor
                        name="content"
                        id="note_editor"
                        label=""
                        placeholder="Enter note content here... Supports images, formulas, and rich formatting."
                        height="400px"
                        :value="old('content', $note->content)"
                        required
                    />
                    @error('content')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </x-card>
        </div>

        <div class="col-xl-4">
            <x-card title="Settings">
                <div class="mb-4">
                    <label for="unit_id" class="form-label fw-medium">Unit <span class="text-danger">*</span></label>
                    <select class="form-select @error('unit_id') is-invalid @enderror"
                            id="unit_id"
                            name="unit_id"
                            required>
                        <option value="">Select Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}"
                                {{ old('unit_id', $note->unit_id) == $unit->id ? 'selected' : '' }}>
                                {{ $unit->course->title }} - Unit {{ $unit->unit_number }}: {{ $unit->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="order" class="form-label fw-medium">Display Order</label>
                    <input type="number"
                           class="form-control @error('order') is-invalid @enderror"
                           id="order"
                           name="order"
                           value="{{ old('order', $note->order) }}"
                           min="0"
                           placeholder="0">
                    @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Lower order numbers appear first</small>
                </div>

                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               id="is_published"
                               name="is_published"
                               value="1"
                               {{ old('is_published', $note->is_published) ? 'checked' : '' }}>
                        <label class="form-check-label fw-medium" for="is_published">
                            Published
                        </label>
                    </div>
                    <small class="text-muted">Unpublished notes are saved as drafts</small>
                </div>
            </x-card>

            <x-card title="Note Info" class="mt-4">
                <div class="small text-muted">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Created:</span>
                        <span>{{ $note->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Last Updated:</span>
                        <span>{{ $note->updated_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Created By:</span>
                        <span>{{ $note->creator->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('admin.notes.show', $note) }}" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle me-1"></i>Cancel
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-1"></i>Update Note
        </button>
    </div>
</form>
@endsection
