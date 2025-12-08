@extends('layouts.frontend')

@section('title', 'Saved Questions - ' . $course->title . ' - TVET Revision')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('learn.index') }}" class="text-decoration-none">{{ $course->title }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Saved Questions</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #f6c23e 0%, #f4b619 100%);">
        <div class="card-body p-4 text-dark">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                    <i class="bi bi-bookmark-fill fs-3"></i>
                </div>
                <div>
                    <h4 class="mb-1 fw-bold">Saved Questions</h4>
                    <p class="mb-0 opacity-75">{{ $bookmarks->total() }} saved question{{ $bookmarks->total() !== 1 ? 's' : '' }} for quick revision</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            @if($bookmarks->count() > 0)
                @foreach($bookmarks as $index => $bookmark)
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge bg-primary me-2">{{ $bookmark->question->unit->title }}</span>
                                    @if($bookmark->question->question_number)
                                        <span class="badge bg-secondary">Q{{ $bookmark->question->question_number }}</span>
                                    @endif
                                </div>
                                <button class="btn btn-sm btn-warning remove-bookmark-btn"
                                        data-question-id="{{ $bookmark->question->id }}"
                                        title="Remove from saved">
                                    <i class="bi bi-bookmark-fill"></i>
                                </button>
                            </div>

                            <div class="question-preview mb-3" style="font-size: 0.95rem;">
                                {!! Str::limit(strip_tags($bookmark->question->question_text), 200) !!}
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>Saved {{ $bookmark->created_at->diffForHumans() }}
                                </small>
                                <a href="{{ route('learn.question', [$bookmark->question->unit->slug, $bookmark->question->slug]) }}"
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>View Answer
                                </a>
                            </div>
                        </div>
                    </div>

                    @if(($index + 1) % 5 === 0)
                        <x-google-ad slot="content" class="mb-3" />
                    @endif
                @endforeach

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $bookmarks->links() }}
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-bookmark text-muted display-1 mb-3"></i>
                        <h5 class="text-muted">No Saved Questions Yet</h5>
                        <p class="text-muted mb-4">Save questions while studying to quickly access them later for revision.</p>
                        <a href="{{ route('learn.index') }}" class="btn btn-primary">
                            <i class="bi bi-book me-2"></i>Start Studying
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-bold">Quick Actions</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="d-grid gap-2">
                        <a href="{{ route('learn.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-house me-2"></i>My Course
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card border-0 shadow-sm mb-4 bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-lightbulb text-warning me-2"></i>Study Tips</h6>
                    <ul class="small text-muted mb-0 ps-3">
                        <li class="mb-2">Save questions you find challenging for later review</li>
                        <li class="mb-2">Review saved questions before exams</li>
                        <li>Focus on understanding concepts, not memorization</li>
                    </ul>
                </div>
            </div>

            <!-- Ad -->
            <x-google-ad slot="sidebar" />
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.remove-bookmark-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const questionId = this.dataset.questionId;
            const card = this.closest('.card');

            fetch(`/learn/bookmark/${questionId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.saved) {
                    card.style.transition = 'opacity 0.3s';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                        // Check if no more bookmarks
                        if (document.querySelectorAll('.remove-bookmark-btn').length === 0) {
                            location.reload();
                        }
                    }, 300);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>
@endpush
