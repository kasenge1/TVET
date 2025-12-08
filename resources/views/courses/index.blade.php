@extends('layouts.frontend')

@section('title', 'All Courses - TVET Revision')
@section('description', 'Browse our comprehensive collection of TVET courses. Find past exam questions and study materials for your KNEC examinations.')

@section('content')
<!-- Hero Section -->
<section class="hero-gradient text-white py-5">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">TVET Courses</h1>
                <p class="lead mb-0 opacity-90">Explore our comprehensive collection of Technical and Vocational Education courses. Find past exam questions and prepare effectively for your exams.</p>
            </div>
            <div class="col-lg-4 text-end d-none d-lg-block">
                <i class="bi bi-mortarboard-fill display-1 opacity-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- Courses Section -->
<section class="py-5">
    <div class="container">
        <!-- Search and Filter -->
        <form action="{{ route('courses.index') }}" method="GET" id="filterForm">
            <div class="row mb-4 g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" placeholder="Search courses..." name="search" id="courseSearch" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select" id="levelFilter" name="level">
                        <option value="">All Levels</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ request('level') == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="sortBy" name="sort">
                        <option value="name" {{ request('sort', 'name') == 'name' ? 'selected' : '' }}>Sort by Name</option>
                        <option value="units" {{ request('sort') == 'units' ? 'selected' : '' }}>Sort by Units</option>
                        <option value="questions" {{ request('sort') == 'questions' ? 'selected' : '' }}>Sort by Questions</option>
                    </select>
                </div>
            </div>
        </form>

        <!-- Results Count -->
        <div class="mb-4">
            <p class="text-muted mb-0">
                Showing {{ $courses->firstItem() ?? 0 }} - {{ $courses->lastItem() ?? 0 }} of {{ $courses->total() }} courses
            </p>
        </div>

        <!-- Courses Grid -->
        <div class="row g-4" id="coursesGrid">
            @forelse($courses as $course)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    @if($course->thumbnail_url)
                    <img src="{{ asset('storage/' . $course->thumbnail_url) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 180px; object-fit: cover;">
                    @else
                    <div class="card-img-top bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 180px;">
                        <i class="bi bi-book text-primary display-3"></i>
                    </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            @if($course->level_display)
                                <span class="badge bg-primary bg-opacity-10 text-primary">{{ $course->level_display }}</span>
                            @else
                                <span></span>
                            @endif
                            @if($course->code)
                                <span class="text-muted small">{{ $course->code }}</span>
                            @endif
                        </div>
                        <h5 class="card-title fw-bold mb-2 course-title">{{ Str::limit($course->title, 50) }}</h5>
                        <p class="card-text text-muted small mb-3 course-description flex-grow-1">{{ Str::limit(strip_tags($course->description), 80) }}</p>
                        <div class="d-flex gap-3 text-muted small">
                            <span><i class="bi bi-collection me-1"></i>{{ $course->units_count }} Units</span>
                            <span><i class="bi bi-question-circle me-1"></i>{{ $course->questions_count }} Questions</span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-primary w-100">
                            <i class="bi bi-eye me-2"></i>View Course
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted opacity-50"></i>
                    <h4 class="mt-3 text-muted">No Courses Available</h4>
                    <p class="text-muted">Check back later for new courses.</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($courses->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $courses->links() }}
        </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="hero-gradient text-white py-5">
    <div class="container text-center position-relative">
        <h2 class="fw-bold mb-3">Ready to Start Learning?</h2>
        <p class="lead mb-4 opacity-90">Join thousands of students preparing for their TVET exams with our comprehensive question bank.</p>
        @guest
            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">
                <i class="bi bi-person-plus me-2"></i>Register Now
            </a>
        @else
            <a href="{{ route('learn.index') }}" class="btn btn-light btn-lg px-5">
                <i class="bi bi-book me-2"></i>Continue Learning
            </a>
        @endguest
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Course cards styling */
    .hover-lift {
        transition: all 0.3s ease;
        border-radius: 16px;
        overflow: hidden;
    }

    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12) !important;
    }

    /* Course cards consistent sizing */
    .course-title {
        min-height: 48px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .course-description {
        min-height: 40px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Pagination styling */
    .pagination {
        gap: 0.25rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .pagination .page-link {
        border-radius: 8px;
        border: none;
        padding: 0.5rem 1rem;
        color: #667eea;
        background: #f8f9fa;
        transition: all 0.2s ease;
    }

    .pagination .page-link:hover {
        background: #667eea;
        color: white;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .pagination .page-item.disabled .page-link {
        background: #e9ecef;
        color: #adb5bd;
    }

    /* Mobile responsive styles for courses page */
    @media (max-width: 767.98px) {
        .course-title {
            min-height: 40px;
            font-size: 0.9rem;
        }

        .course-description {
            min-height: 32px;
            font-size: 0.75rem;
        }

        .pagination .page-link {
            padding: 0.35rem 0.6rem;
            font-size: 0.75rem;
        }

        /* Course stats */
        .d-flex.gap-3.text-muted.small span {
            font-size: 0.7rem;
        }
    }

    @media (max-width: 575.98px) {
        .course-title {
            min-height: 36px;
            font-size: 0.85rem;
        }

        .pagination .page-link {
            padding: 0.3rem 0.5rem;
            font-size: 0.7rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('courseSearch');
    const levelFilter = document.getElementById('levelFilter');
    const sortBy = document.getElementById('sortBy');
    let searchTimeout;

    // Submit form on level or sort change
    levelFilter.addEventListener('change', function() {
        filterForm.submit();
    });

    sortBy.addEventListener('change', function() {
        filterForm.submit();
    });

    // Debounced search - submit after user stops typing
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            filterForm.submit();
        }, 500);
    });

    // Submit on Enter key
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
