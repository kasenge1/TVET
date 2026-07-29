@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Study Notes')
@section('page-actions')
    <a href="{{ route('admin.notes.create') }}" class="btn-modern btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Add Note
    </a>
@endsection

@section('main')
<x-card>
    <!-- Filters -->
    <form method="GET" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <select name="unit" class="form-select">
                    <option value="">All Units</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->course->title }} - Unit {{ $unit->unit_number }}: {{ $unit->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="published" class="form-select">
                    <option value="">All Status</option>
                    <option value="1" {{ request('published') === '1' ? 'selected' : '' }}>Published</option>
                    <option value="0" {{ request('published') === '0' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="{{ route('admin.notes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>Clear
                </a>
            </div>
        </div>
    </form>

    <!-- Notes Table -->
    <div class="table-responsive">
        <table class="table-modern table align-middle mb-0">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Title</th>
                    <th>Unit</th>
                    <th>Status</th>
                    <th>Order</th>
                    <th>Created By</th>
                    <th>Created</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notes as $index => $note)
                    <tr>
                        <td class="text-muted">{{ ($notes->currentPage() - 1) * $notes->perPage() + $index + 1 }}</td>
                        <td>
                            <a href="{{ route('admin.notes.show', $note) }}" class="fw-medium text-decoration-none">
                                {{ Str::limit($note->title, 50) }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.units.show', $note->unit) }}" class="text-decoration-none">
                                <small class="text-muted">{{ $note->unit->course->title }}</small><br>
                                Unit {{ $note->unit->unit_number }}: {{ Str::limit($note->unit->title, 30) }}
                            </a>
                        </td>
                        <td>
                            @if($note->is_published)
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-warning text-dark">Draft</span>
                            @endif
                        </td>
                        <td>{{ $note->order }}</td>
                        <td>{{ $note->creator->name ?? 'N/A' }}</td>
                        <td>{{ $note->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.notes.show', $note) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.notes.edit', $note) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.notes.destroy', $note) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this note?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-journal-text fs-1 d-block mb-2"></i>
                                <p class="mb-0">No notes found</p>
                                <a href="{{ route('admin.notes.create') }}" class="btn btn-primary btn-sm mt-3">
                                    <i class="bi bi-plus-circle me-1"></i>Create First Note
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($notes->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $notes->withQueryString()->links() }}
        </div>
    @endif
</x-card>
@endsection
