@extends('layouts.frontend')

@section('title', 'All Courses - TVET Revision')
@section('description', 'Browse our comprehensive collection of TVET courses. Find past exam questions and study materials for your KNEC examinations.')

@section('content')
<!-- Hero Section -->
<section class="fe-page-hero text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="fe-hero-title mb-3" style="font-size: 2.5rem;">TVET Courses</h1>
                <p class="fe-hero-subtitle mb-0">Explore our comprehensive collection of Technical and Vocational Education courses. Find past exam questions and prepare effectively for your exams.</p>
            </div>
            <div class="col-lg-4 text-end d-none d-lg-block">
                <i class="bi bi-mortarboard-fill display-1 opacity-25"></i>
            </div>
        </div>
    </div>
</section>

<!-- Courses Section -->
<section class="fe-section">
    <div class="container">
        <!-- Search and Filter -->
        <form action="{{ route('courses.index') }}" method="GET" id="filterForm">
            <div class="fe-sidebar-card mb-4" style="padding: 1.25rem;">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold" style="font-size: 0.8rem; color: var(--fe-text-secondary); margin-bottom: 0.4rem;">Search</label>
                        <div class="position-relative">
                            <i class="bi bi-search position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); color: var(--fe-text-muted); font-size: 0.9rem;"></i>
                            <input type="text" class="fe-search-input w-100" style="padding-left: 2.5rem;" placeholder="Search courses..." name="search" id="courseSearch" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size: 0.8rem; color: var(--fe-text-secondary); margin-bottom: 0.4rem;">Level</label>
                        <select class="fe-filter-select w-100" id="levelFilter" name="level">
                            <option value="">All Levels</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ request('level') == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="font-size: 0.8rem; color: var(--fe-text-secondary); margin-bottom: 0.4rem;">Sort By</label>
                        <select class="fe-filter-select w-100" id="sortBy" name="sort">
                            <option value="name" {{ request('sort', 'name') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="units" {{ request('sort') == 'units' ? 'selected' : '' }}>Units</option>
                            <option value="questions" {{ request('sort') == 'questions' ? 'selected' : '' }}>Questions</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>

        <!-- Results Count -->
        <div class="mb-4">
            <p class="mb-0" style="font-size: 0.85rem; color: var(--fe-text-muted);">
                Showing {{ $courses->firstItem() ?? 0 }} - {{ $courses->lastItem() ?? 0 }} of {{ $courses->total() }} courses
            </p>
        </div>

        <!-- Courses Grid -->
        <div class="row g-4" id="coursesGrid">
            @forelse($courses as $course)
            <div class="col-md-6 col-lg-4">
                <div class="fe-card">
                    <div class="fe-card-img">
                        @if($course->thumbnail_url)
                        <img src="{{ asset('storage/' . $course->thumbnail_url) }}" alt="{{ $course->title }}">
                        @else
                        <div class="fe-card-img-placeholder"><i class="bi bi-book"></i></div>
                        @endif
                        @if($course->level_display)
                            <span class="fe-card-badge">{{ $course->level_display }}</span>
                        @endif
                    </div>
                    <div class="fe-card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="fe-card-meta mb-0">
                                <i class="bi bi-collection me-1"></i>{{ $course->units_count }} Units
                            </div>
                            @if($course->code)
                                <span style="font-size: 0.7rem; color: var(--fe-text-muted);">{{ $course->code }}</span>
                            @endif
                        </div>
                        <h5 class="fe-card-title">{{ Str::limit($course->title, 50) }}</h5>
                        <p class="fe-card-desc mb-3">{{ Str::limit(strip_tags($course->description), 80) }}</p>
                        <div class="d-flex gap-3" style="font-size: 0.75rem; color: var(--fe-text-muted);">
                            <span><i class="bi bi-question-circle me-1"></i>{{ $course->questions_count }} Questions</span>
                        </div>
                    </div>
                    <div class="fe-card-footer">
                        <a href="{{ route('courses.show', $course) }}" class="fe-btn fe-btn-primary w-100">
                            View Course <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="fe-empty-state">
                    <i class="bi bi-inbox d-block"></i>
                    <h5>No Courses Available</h5>
                    <p>Check back later for new courses.</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($courses->hasPages())
        <div class="d-flex justify-content-center mt-5">
            <nav class="fe-pagination">{{ $courses->links() }}</nav>
        </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="fe-cta text-center">
    <div class="container">
        <h2 class="fe-cta-title">Ready to Start Learning?</h2>
        <p class="fe-cta-subtitle">Join thousands of students preparing for their TVET exams with our comprehensive question bank.</p>
        @guest
            <a href="{{ route('register') }}" class="fe-btn fe-btn-white fe-btn-lg">
                <i class="bi bi-person-plus me-2"></i>Register Now
            </a>
        @else
            <a href="{{ route('learn.index') }}" class="fe-btn fe-btn-white fe-btn-lg">
                <i class="bi bi-book me-2"></i>Continue Learning
            </a>
        @endguest
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('courseSearch');
    const levelFilter = document.getElementById('levelFilter');
    const sortBy = document.getElementById('sortBy');
    let searchTimeout;

    levelFilter.addEventListener('change', function() { filterForm.submit(); });
    sortBy.addEventListener('change', function() { filterForm.submit(); });

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() { filterForm.submit(); }, 500);
    });

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimeout);
            filterForm.submit();
        }
    });
});
</script>
@endpush
