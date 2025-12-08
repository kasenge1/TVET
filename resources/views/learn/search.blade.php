@extends('layouts.frontend')

@section('title', 'Search - TVET Revision')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('learn.index') }}" class="text-decoration-none">My Course</a></li>
            <li class="breadcrumb-item active" aria-current="page">Search</li>
        </ol>
    </nav>

    <!-- Search Header -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body p-4">
            <form action="{{ route('learn.search') }}" method="GET">
                <div class="row g-2">
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text"
                                   name="q"
                                   class="form-control border-0"
                                   placeholder="Search questions in {{ $course->title }}..."
                                   value="{{ $searchQuery }}"
                                   autofocus>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-light px-4">
                            Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($searchQuery)
        <!-- Search Results -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">
                @if($questions instanceof \Illuminate\Pagination\LengthAwarePaginator && $questions->total() > 0)
                    Found <strong>{{ $questions->total() }}</strong> result{{ $questions->total() != 1 ? 's' : '' }} for "<strong>{{ $searchQuery }}</strong>"
                @else
                    No results found for "<strong>{{ $searchQuery }}</strong>"
                @endif
            </h6>
            <a href="{{ route('learn.search') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i>Clear
            </a>
        </div>

        @if($questions instanceof \Illuminate\Pagination\LengthAwarePaginator && $questions->count() > 0)
            @foreach($questions as $question)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-primary">{{ $question->unit->title }}</span>
                            @if(in_array($question->id, $savedIds))
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-bookmark-fill me-1"></i>Saved
                                </span>
                            @endif
                        </div>
                        <button class="btn btn-sm bookmark-btn {{ in_array($question->id, $savedIds) ? 'btn-warning' : 'btn-outline-warning' }}"
                                data-question-id="{{ $question->id }}"
                                title="{{ in_array($question->id, $savedIds) ? 'Remove bookmark' : 'Save question' }}">
                            <i class="bi bi-bookmark{{ in_array($question->id, $savedIds) ? '-fill' : '' }}"></i>
                        </button>
                    </div>

                    <div class="question-text mb-3">
                        {!! Str::limit(strip_tags($question->question_text), 200) !!}
                    </div>

                    @if($question->question_images && count($question->question_images) > 0)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $question->question_images[0]) }}"
                                 alt="Question Image"
                                 class="img-fluid rounded"
                                 style="max-height: 150px;">
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-eye me-1"></i>{{ $question->view_count ?? 0 }} views
                        </small>
                        <a href="{{ route('learn.question', [$question->unit->slug, $question->slug]) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye me-1"></i>View Answer
                        </a>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Pagination -->
            @if($questions->hasPages())
            <div class="mt-4">
                {{ $questions->appends(['q' => $searchQuery])->links() }}
            </div>
            @endif
        @else
            <!-- No Results -->
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-search display-5 text-muted"></i>
                    </div>
                    <h5 class="text-muted">No Results Found</h5>
                    <p class="text-muted mb-4">We couldn't find any questions matching "<strong>{{ $searchQuery }}</strong>"</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('learn.search') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-1"></i>New Search
                        </a>
                        <a href="{{ route('learn.index') }}" class="btn btn-primary">
                            <i class="bi bi-book me-1"></i>Browse Course
                        </a>
                    </div>
                </div>
            </div>
        @endif
    @else
        <!-- Search Tips -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-lightbulb me-2 text-warning"></i>Search Tips</h6>
                    </div>
                    <div class="card-body pt-0">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 d-flex align-items-start">
                                <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                                <span>Use specific keywords related to your topic</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                                <span>Try different variations of your search terms</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                                <span>Search looks through question text and answers</span>
                            </li>
                            <li class="d-flex align-items-start">
                                <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                                <span>Results are from: <strong>{{ $course->title }}</strong></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h6 class="mb-0 fw-bold">Quick Links</h6>
                    </div>
                    <div class="card-body pt-0">
                        <div class="d-grid gap-2">
                            <a href="{{ route('learn.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-book me-2"></i>Browse All Units
                            </a>
                            <a href="{{ route('learn.saved') }}" class="btn btn-outline-warning">
                                <i class="bi bi-bookmark-fill me-2"></i>Saved Questions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bookmark toggle functionality
    document.querySelectorAll('.bookmark-btn').forEach(button => {
        button.addEventListener('click', function() {
            const questionId = this.dataset.questionId;
            const btn = this;
            const icon = btn.querySelector('i');

            fetch(`{{ url('learn/bookmark') }}/${questionId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.saved) {
                    btn.classList.remove('btn-outline-warning');
                    btn.classList.add('btn-warning');
                    icon.classList.remove('bi-bookmark');
                    icon.classList.add('bi-bookmark-fill');
                    btn.title = 'Remove bookmark';
                } else {
                    btn.classList.remove('btn-warning');
                    btn.classList.add('btn-outline-warning');
                    icon.classList.remove('bi-bookmark-fill');
                    icon.classList.add('bi-bookmark');
                    btn.title = 'Save question';
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>
@endpush
