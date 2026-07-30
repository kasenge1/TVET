@extends('layouts.admin')

@section('page-header', true)
@section('page-title', $note->title)
@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.notes.edit', $note) }}" class="btn-modern btn btn-primary">
            <i class="bi bi-pencil me-2"></i>Edit Note
        </a>
        <a href="{{ route('admin.notes.index') }}" class="btn-modern btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Notes
        </a>
    </div>
@endsection

@section('main')
<div class="row g-4">
    <div class="col-xl-8">
        <x-card title="Note Content">
            <div class="note-content">
                {!! $note->content !!}
            </div>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Details">
            <table class="table table-borderless mb-0">
                <tr>
                    <th width="100" class="text-muted">Status:</th>
                    <td>
                        @if($note->is_published)
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-warning text-dark">Draft</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="text-muted">Unit:</th>
                    <td>
                        <a href="{{ route('admin.units.show', $note->unit) }}" class="text-decoration-none">
                            {{ $note->unit->course->title }}<br>
                            <small>Unit {{ $note->unit->unit_number }}: {{ $note->unit->title }}</small>
                        </a>
                    </td>
                </tr>
                <tr>
                    <th class="text-muted">Order:</th>
                    <td>{{ $note->order }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Created:</th>
                    <td>{{ $note->created_at->format('F d, Y') }}<br>
                        <small class="text-muted">{{ $note->created_at->diffForHumans() }}</small>
                    </td>
                </tr>
                <tr>
                    <th class="text-muted">Updated:</th>
                    <td>{{ $note->updated_at->format('F d, Y') }}<br>
                        <small class="text-muted">{{ $note->updated_at->diffForHumans() }}</small>
                    </td>
                </tr>
                <tr>
                    <th class="text-muted">Created By:</th>
                    <td>{{ $note->creator->name ?? 'N/A' }}</td>
                </tr>
            </table>
        </x-card>

        <x-card title="Quick Actions" class="mt-4">
            <div class="d-grid gap-2">
                <a href="{{ route('admin.notes.edit', $note) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Edit Note
                </a>
                <a href="{{ route('admin.notes.index', ['unit' => $note->unit_id]) }}" class="btn btn-outline-info">
                    <i class="bi bi-journal-text me-2"></i>All Notes for This Unit
                </a>
                <a href="{{ route('admin.units.show', $note->unit) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-box me-2"></i>View Unit
                </a>
                <hr class="my-2">
                <form action="{{ route('admin.notes.destroy', $note) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this note?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-trash me-2"></i>Delete Note
                    </button>
                </form>
            </div>
        </x-card>
    </div>
</div>
@endsection

@push('styles')
<style>
.note-content {
    line-height: 1.7;
    font-size: 1rem;
}
.note-content p {
    margin-bottom: 1rem;
}
.note-content ul, .note-content ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}
.note-content h1, .note-content h2, .note-content h3,
.note-content h4, .note-content h5, .note-content h6 {
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}
.note-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}
.note-content blockquote {
    border-left: 4px solid #2563eb;
    padding-left: 1rem;
    margin-left: 0;
    color: #6c757d;
    font-style: italic;
}
.note-content pre {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    overflow-x: auto;
}
.note-content .ql-formula {
    background: #e8f4fd;
    padding: 2px 6px;
    border-radius: 4px;
}
</style>
@endpush
