@extends('layouts.admin')

@section('page-header', true)
@section('page-title')
    {{ $course->title }}
    @if($course->is_published)
        <span class="badge-modern badge bg-success ms-2">Published</span>
    @else
        <span class="badge-modern badge bg-secondary ms-2">Draft</span>
    @endif
@endsection
@section('page-actions')
    <a href="{{ route('admin.courses.edit', $course) }}" class="btn-modern btn btn-primary">
        <i class="bi bi-pencil me-2"></i>Edit Course
    </a>
@endsection

@section('main')
<div class="row g-4">
    <div class="col-xl-8">
        <x-card title="Course Information">
            <table class="table table-borderless">
                <tr>
                    <th width="150">Course Code:</th>
                    <td><span class="badge-modern badge bg-light text-dark">{{ $course->code }}</span></td>
                </tr>
                <tr>
                    <th>Level:</th>
                    <td>{{ $course->level_display ?: 'No Level' }}</td>
                </tr>
                <tr>
                    <th>Description:</th>
                    <td>{{ $course->description ?: 'No description provided' }}</td>
                </tr>
                <tr>
                    <th>Created By:</th>
                    <td>{{ $course->creator->name }}</td>
                </tr>
            </table>
        </x-card>

        <x-card title="Course Units" class="mt-4">
            @if($course->units->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($course->units as $unit)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Unit {{ $unit->unit_number }}: {{ $unit->title }}</h6>
                                    <span class="badge-modern badge bg-info">{{ $unit->questions->count() }} Questions</span>
                                </div>
                                <div class="btn-group-modern btn-group btn-group-sm">
                                    <a href="{{ route('admin.units.show', $unit) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-collection display-3 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">No units yet</h5>
                    <a href="{{ route('admin.units.create', ['course' => $course->id]) }}" class="btn-modern btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add First Unit
                    </a>
                </div>
            @endif
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Statistics">
            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <span>Total Units</span>
                    <span class="fw-bold">{{ $course->units->count() }}</span>
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <span>Total Enrollments</span>
                    <span class="fw-bold">{{ $course->enrollments->count() }}</span>
                </div>
            </div>
        </x-card>

        <x-card title="Quick Actions" class="mt-4">
            <div class="d-grid gap-2">
                <a href="{{ route('admin.units.create', ['course' => $course->id]) }}" class="btn btn-outline-primary">
                    <i class="bi bi-plus-circle me-2"></i>Add Unit
                </a>
                @if($course->is_published)
                    <form action="{{ route('admin.courses.unpublish', $course) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning w-100">
                            <i class="bi bi-eye-slash me-2"></i>Unpublish
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.courses.publish', $course) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-success w-100">
                            <i class="bi bi-check-circle me-2"></i>Publish
                        </button>
                    </form>
                @endif
            </div>
        </x-card>
    </div>
</div>
@endsection
