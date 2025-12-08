@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Send Notification')

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card title="Create Notification">
            <form action="{{ route('admin.notifications.send') }}" method="POST" id="notificationForm">
                @csrf

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Send notifications to specific users based on their course enrollment or to all users.
                </div>

                <!-- Target Selection -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Target Audience <span class="text-danger">*</span></label>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-check card p-3 h-100">
                                <input class="form-check-input" type="radio" name="target_type" id="target_course" value="course" checked>
                                <label class="form-check-label w-100" for="target_course">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-book text-primary fs-4 me-2"></i>
                                        <div>
                                            <strong>Course Students</strong>
                                            <div class="text-muted small">Target specific course</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check card p-3 h-100">
                                <input class="form-check-input" type="radio" name="target_type" id="target_all" value="all">
                                <label class="form-check-label w-100" for="target_all">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-people text-success fs-4 me-2"></i>
                                        <div>
                                            <strong>All Students</strong>
                                            <div class="text-muted small">Send to everyone</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check card p-3 h-100">
                                <input class="form-check-input" type="radio" name="target_type" id="target_admins" value="admins">
                                <label class="form-check-label w-100" for="target_admins">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-shield text-danger fs-4 me-2"></i>
                                        <div>
                                            <strong>Admins Only</strong>
                                            <div class="text-muted small">Admin team</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    @error('target_type')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Course Selection (shown when target_type is course) -->
                <div class="mb-4" id="courseSelectWrapper">
                    <label for="course_id" class="form-label fw-medium">Select Course <span class="text-danger">*</span></label>
                    <select class="form-select @error('course_id') is-invalid @enderror"
                            id="course_id"
                            name="course_id">
                        <option value="">-- Select a course --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="studentCount" class="text-muted small mt-1" style="display: none;">
                        <i class="bi bi-people me-1"></i><span id="studentCountText">0 students enrolled</span>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Notification Content -->
                <div class="mb-4">
                    <label for="title" class="form-label fw-medium">Notification Title <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('title') is-invalid @enderror"
                           id="title"
                           name="title"
                           value="{{ old('title') }}"
                           placeholder="e.g., New Study Materials Available"
                           maxlength="255">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="message" class="form-label fw-medium">Message <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('message') is-invalid @enderror"
                              id="message"
                              name="message"
                              rows="4"
                              placeholder="Enter your notification message..."
                              maxlength="1000">{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="text-muted small mt-1">
                        <span id="messageCount">0</span>/1000 characters
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="icon" class="form-label fw-medium">Icon</label>
                        <select class="form-select @error('icon') is-invalid @enderror"
                                id="icon"
                                name="icon">
                            @foreach($icons as $value => $label)
                                <option value="{{ $value }}" {{ old('icon', 'bell') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="icon_color" class="form-label fw-medium">Icon Color</label>
                        <select class="form-select @error('icon_color') is-invalid @enderror"
                                id="icon_color"
                                name="icon_color">
                            @foreach($colors as $value => $label)
                                <option value="{{ $value }}" {{ old('icon_color', 'primary') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('icon_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="action_url" class="form-label fw-medium">Action URL <span class="text-muted">(optional)</span></label>
                    <input type="url"
                           class="form-control @error('action_url') is-invalid @enderror"
                           id="action_url"
                           name="action_url"
                           value="{{ old('action_url') }}"
                           placeholder="https://example.com/page">
                    @error('action_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Link users will be redirected to when clicking the notification</small>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="send_email" name="send_email" value="1" {{ old('send_email') ? 'checked' : '' }}>
                        <label class="form-check-label" for="send_email">
                            <i class="bi bi-envelope me-1"></i>Also send email notification
                        </label>
                    </div>
                    <small class="text-muted">Users who have email notifications disabled will not receive emails</small>
                </div>

                <div class="border-top pt-4">
                    <button type="submit" class="btn-modern btn btn-primary px-4">
                        <i class="bi bi-send me-2"></i>Send Notification
                    </button>
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <!-- Preview Card -->
        <x-card title="Preview" class="border-primary">
            <div class="notification-preview">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0 me-3">
                        <div id="previewIcon" class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-bell text-primary fs-5"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 id="previewTitle" class="mb-1 fw-bold">Notification Title</h6>
                        <p id="previewMessage" class="mb-1 text-muted small">Your notification message will appear here...</p>
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>Just now
                        </small>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Tips Card -->
        <x-card title="Tips" class="mt-4">
            <ul class="list-unstyled mb-0 small">
                <li class="mb-3 d-flex align-items-start">
                    <i class="bi bi-lightbulb text-warning me-2 mt-1"></i>
                    <div>
                        <strong>Be Concise</strong>
                        <div class="text-muted">Keep your message short and clear</div>
                    </div>
                </li>
                <li class="mb-3 d-flex align-items-start">
                    <i class="bi bi-bullseye text-danger me-2 mt-1"></i>
                    <div>
                        <strong>Target Wisely</strong>
                        <div class="text-muted">Only send relevant notifications</div>
                    </div>
                </li>
                <li class="d-flex align-items-start">
                    <i class="bi bi-link-45deg text-primary me-2 mt-1"></i>
                    <div>
                        <strong>Add Action URL</strong>
                        <div class="text-muted">Direct users to relevant content</div>
                    </div>
                </li>
            </ul>
        </x-card>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const courseSelectWrapper = document.getElementById('courseSelectWrapper');
    const courseSelect = document.getElementById('course_id');
    const studentCountDiv = document.getElementById('studentCount');
    const studentCountText = document.getElementById('studentCountText');
    const targetRadios = document.querySelectorAll('input[name="target_type"]');
    const titleInput = document.getElementById('title');
    const messageInput = document.getElementById('message');
    const iconSelect = document.getElementById('icon');
    const colorSelect = document.getElementById('icon_color');
    const messageCount = document.getElementById('messageCount');
    const previewTitle = document.getElementById('previewTitle');
    const previewMessage = document.getElementById('previewMessage');
    const previewIcon = document.getElementById('previewIcon');

    // Toggle course selection visibility
    function toggleCourseSelect() {
        const selectedTarget = document.querySelector('input[name="target_type"]:checked').value;
        if (selectedTarget === 'course') {
            courseSelectWrapper.style.display = 'block';
        } else {
            courseSelectWrapper.style.display = 'none';
        }
    }

    targetRadios.forEach(radio => {
        radio.addEventListener('change', toggleCourseSelect);
    });

    // Fetch student count when course is selected
    courseSelect.addEventListener('change', function() {
        const courseId = this.value;
        if (courseId) {
            fetch(`{{ url('admin/notifications/course-students') }}/${courseId}`)
                .then(response => response.json())
                .then(data => {
                    studentCountText.textContent = `${data.count} student(s) enrolled`;
                    studentCountDiv.style.display = 'block';
                })
                .catch(error => {
                    studentCountDiv.style.display = 'none';
                });
        } else {
            studentCountDiv.style.display = 'none';
        }
    });

    // Message character count
    messageInput.addEventListener('input', function() {
        messageCount.textContent = this.value.length;
    });

    // Live preview updates
    titleInput.addEventListener('input', function() {
        previewTitle.textContent = this.value || 'Notification Title';
    });

    messageInput.addEventListener('input', function() {
        previewMessage.textContent = this.value || 'Your notification message will appear here...';
    });

    function updatePreviewIcon() {
        const icon = iconSelect.value;
        const color = colorSelect.value;
        previewIcon.className = `rounded-circle bg-${color} bg-opacity-10 d-flex align-items-center justify-content-center`;
        previewIcon.style.width = '44px';
        previewIcon.style.height = '44px';
        previewIcon.innerHTML = `<i class="bi bi-${icon} text-${color} fs-5"></i>`;
    }

    iconSelect.addEventListener('change', updatePreviewIcon);
    colorSelect.addEventListener('change', updatePreviewIcon);

    // Initial state
    toggleCourseSelect();
    if (courseSelect.value) {
        courseSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection
