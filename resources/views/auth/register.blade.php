@extends('layouts.guest')

@section('title', 'Create Account - TVET Revision')

@section('main')
<div class="auth-header">
    <h2>Create Account</h2>
    <p>Start your learning journey today</p>
</div>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-4">
        <label for="name" class="form-label">Full Name</label>
        <div class="input-with-icon">
            <i class="bi bi-person input-icon"></i>
            <input type="text"
                   class="form-control @error('name') is-invalid @enderror"
                   id="name"
                   name="name"
                   value="{{ old('name') }}"
                   required
                   autofocus
                   placeholder="Enter your full name">
        </div>
        @error('name')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-with-icon">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   placeholder="name@example.com">
        </div>
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <div class="input-with-icon password-field">
            <i class="bi bi-lock input-icon"></i>
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   id="password"
                   name="password"
                   required
                   placeholder="Create password">
            <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <div class="input-with-icon password-field">
            <i class="bi bi-lock-fill input-icon"></i>
            <input type="password"
                   class="form-control"
                   id="password_confirmation"
                   name="password_confirmation"
                   required
                   placeholder="Confirm password">
            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <!-- Course Selection -->
    <div class="mb-4">
        <label for="course_id" class="form-label">Select Your Course</label>
        <select class="form-select @error('course_id') is-invalid @enderror"
                id="course_id"
                name="course_id"
                required>
            <option value="">Search and select your course...</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}"
                        {{ old('course_id') == $course->id ? 'selected' : '' }}
                        data-units="{{ $course->units_count }}"
                        data-questions="{{ $course->questions_count }}"
                        data-level="{{ $course->level_display }}">
                    {{ $course->title }}{{ $course->level_display ? ' - ' . $course->level_display : '' }}
                </option>
            @endforeach
        </select>
        @error('course_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror

        <!-- Course Info Display -->
        <div id="courseInfo" class="course-info-box mt-3" style="display: none;">
            <div class="d-flex justify-content-between align-items-center">
                <span class="small"><i class="bi bi-journal-text me-1" style="color: #667eea;"></i><strong id="infoUnits">0</strong> Units</span>
                <span class="small"><i class="bi bi-question-circle me-1" style="color: #667eea;"></i><strong id="infoQuestions">0</strong> Questions</span>
            </div>
        </div>

        <!-- Warning -->
        <div class="warning-box mt-3">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <p><strong>Important:</strong> You cannot change your course after registration. Choose carefully.</p>
        </div>
    </div>

    <!-- reCAPTCHA -->
    <x-recaptcha form="register" />

    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-rocket-takeoff me-2"></i>Create Account
        </button>
    </div>

    <div class="auth-footer">
        <span>Already have an account?</span>
        <a href="{{ route('login') }}" class="ms-1">Sign in</a>
    </div>
</form>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<style>
    .password-field {
        position: relative;
    }
    .password-field .form-control {
        padding-right: 3rem;
    }
    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        padding: 0;
        z-index: 5;
        transition: color 0.2s;
    }
    .password-toggle:hover {
        color: #667eea;
    }
    .ts-wrapper.form-select {
        padding: 0;
        border: none;
    }
    .ts-control {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        background-color: #f9fafb;
        min-height: 52px;
    }
    .ts-control:hover {
        border-color: #d1d5db;
    }
    .ts-wrapper.focus .ts-control {
        border-color: #667eea;
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }
    .ts-dropdown {
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        margin-top: 4px;
        overflow: hidden;
    }
    .ts-dropdown .option {
        padding: 0.75rem 1rem;
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .ts-dropdown .option:hover {
        background-color: #f3f4f6;
    }
    .ts-dropdown .option.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .ts-dropdown .option:hover:not(.active) {
        background-color: rgba(102, 126, 234, 0.1);
    }
    .ts-dropdown .option.active .text-muted {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    .ts-dropdown .option:hover:not(.active) .text-muted {
        color: #667eea !important;
    }
    .ts-dropdown .option.active .badge {
        background-color: rgba(255, 255, 255, 0.2) !important;
    }
    .ts-dropdown .option:hover:not(.active) .badge {
        background-color: rgba(102, 126, 234, 0.2) !important;
        color: #667eea !important;
    }
    .ts-control input {
        font-size: 0.95rem;
    }
    .ts-control input::placeholder {
        color: #9ca3af;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
// Password toggle function
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const courseSelect = document.getElementById('course_id');
    const courseInfo = document.getElementById('courseInfo');
    const infoUnits = document.getElementById('infoUnits');
    const infoQuestions = document.getElementById('infoQuestions');

    // Initialize Tom Select for searchable dropdown
    const tomSelect = new TomSelect(courseSelect, {
        placeholder: 'Search and select your course...',
        allowEmptyOption: true,
        sortField: {
            field: 'text',
            direction: 'asc'
        },
        render: {
            option: function(data, escape) {
                const option = courseSelect.querySelector(`option[value="${data.value}"]`);
                if (!option || !data.value) return `<div class="py-2">${escape(data.text)}</div>`;

                const level = option.dataset.level || '';
                const units = option.dataset.units || '0';
                const questions = option.dataset.questions || '0';

                return `<div class="py-1">
                    <div class="fw-semibold">${escape(data.text.split(' - ')[0])}</div>
                    <div class="mt-1">
                        ${level ? `<span class="badge bg-secondary me-1">${escape(level)}</span>` : ''}
                        <small class="text-muted">${escape(units)} Units &bull; ${escape(questions)} Questions</small>
                    </div>
                </div>`;
            },
            item: function(data, escape) {
                return `<div>${escape(data.text)}</div>`;
            }
        }
    });

    // Show course info when selected
    tomSelect.on('change', function(value) {
        if (value) {
            const option = courseSelect.querySelector(`option[value="${value}"]`);
            if (option) {
                infoUnits.textContent = option.dataset.units || '0';
                infoQuestions.textContent = option.dataset.questions || '0';
                courseInfo.style.display = 'block';
            }
        } else {
            courseInfo.style.display = 'none';
        }
    });

    // Trigger change if there's an old value
    if (courseSelect.value) {
        tomSelect.trigger('change', courseSelect.value);
    }
});
</script>
@endpush
