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
        <label for="phone_input" class="form-label">Phone Number <span class="text-danger">*</span></label>
        <input type="tel"
               class="form-control @error('phone_number') is-invalid @enderror"
               id="phone_input"
               placeholder="712 345 678">
        {{-- Hidden field that receives the full E.164 number e.g. +254712345678 --}}
        <input type="hidden" name="phone_number" id="phone_number" value="{{ old('phone_number') }}">
        @error('phone_number')
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
        <select id="course_id"
                name="course_id"
                required>
            <option value=""></option>
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
                <span class="small"><i class="bi bi-journal-text me-1" style="color: #2563eb;"></i><strong id="infoUnits">0</strong> Units</span>
                <span class="small"><i class="bi bi-question-circle me-1" style="color: #2563eb;"></i><strong id="infoQuestions">0</strong> Questions</span>
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
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23/build/css/intlTelInput.css">
<style>
    /* intl-tel-input overrides to match the page style */
    .iti {
        display: block;
        width: 100%;
    }
    .iti__flag-container {
        z-index: 10;
    }
    #phone_input {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        padding-left: 3.5rem;
        background-color: #f9fafb;
        height: 52px;
        width: 100%;
        font-size: 0.95rem;
    }
    #phone_input:focus {
        border-color: #2563eb;
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        outline: none;
    }
    .iti__selected-dial-code {
        font-size: 0.9rem;
    }
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
        color: #2563eb;
    }
    /* Tom Select Custom Styles - Match other inputs */
    #course_id {
        display: none;
    }
    .ts-wrapper {
        position: relative;
        box-sizing: border-box;
        width: 100%;
    }
    .ts-wrapper.single .ts-control {
        position: relative;
        display: flex;
        align-items: center;
    }
    .ts-control {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 0.875rem 1rem;
        background-color: #f9fafb;
        min-height: 52px;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
        cursor: text;
        width: 100%;
    }
    .ts-control:hover {
        border-color: #d1d5db;
        background-color: #fff;
    }
    .ts-wrapper.focus .ts-control {
        border-color: #2563eb;
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        outline: none;
    }
    .ts-wrapper .ts-control .item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0;
    }
    /* Dropdown - clean list, no wrapper */
    .ts-dropdown {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        margin-top: 8px;
        background: #fff;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1000;
    }
    .ts-dropdown .ts-dropdown-content {
        max-height: 280px;
        padding: 0.5rem;
    }
    .ts-dropdown .option {
        padding: 0.75rem 1rem;
        transition: all 0.15s ease;
        cursor: pointer;
        border-radius: 8px;
    }
    .ts-dropdown .option:hover {
        background-color: #f0f7ff;
    }
    .ts-dropdown .option.active {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
    }
    .ts-dropdown .option:hover:not(.active) {
        background-color: #f0f7ff;
    }
    .ts-dropdown .option.active .text-muted {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    .ts-dropdown .option:hover:not(.active) .text-muted {
        color: #2563eb !important;
    }
    .ts-dropdown .option.active .badge {
        background-color: rgba(255, 255, 255, 0.25) !important;
        color: #fff !important;
    }
    .ts-dropdown .option:hover:not(.active) .badge {
        background-color: #dbeafe !important;
        color: #1d4ed8 !important;
    }
    .ts-control input {
        font-size: 0.95rem;
        color: #1e293b;
        cursor: text;
        font-weight: 500;
        padding: 0;
        border: none;
        background: transparent;
    }
    .ts-control input:focus {
        outline: none;
        box-shadow: none;
    }
    .ts-control input::placeholder {
        color: #64748b;
        opacity: 1;
        font-weight: 500;
    }
    /* Hide placeholder when there's a value or items selected */
    .ts-wrapper.has-items .ts-control input::placeholder {
        opacity: 0;
    }
    .ts-wrapper.focus .ts-control input::placeholder {
        opacity: 0;
    }
    /* Clear button styling */
    .ts-wrapper .ts-control .clear-button {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        padding: 0.25rem;
        font-size: 1.1rem;
        line-height: 1;
        z-index: 5;
        transition: color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .ts-wrapper .ts-control .clear-button:hover {
        color: #ef4444;
    }
    /* Dropdown single select plugin */
    .ts-wrapper.single .ts-control .item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    /* Search input inside dropdown - clean look */
    .ts-dropdown .ts-dropdown-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: none;
        border-bottom: 1px solid #f3f4f6;
        border-radius: 0;
        font-size: 0.95rem;
        margin-bottom: 0;
    }
    .ts-dropdown .ts-dropdown-input:focus {
        border-color: #2563eb;
        box-shadow: none;
        outline: none;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23/build/js/intlTelInput.min.js"></script>
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

    // --- intl-tel-input setup ---
    const phoneInput = document.getElementById('phone_input');
    const phoneHidden = document.getElementById('phone_number');

    const iti = window.intlTelInput(phoneInput, {
        initialCountry: 'ke',
        separateDialCode: true,
        loadUtils: () => import('https://cdn.jsdelivr.net/npm/intl-tel-input@23/build/js/utils.js'),
    });

    // Restore old value if validation failed
    const oldVal = phoneHidden.value;
    if (oldVal) {
        iti.setNumber(oldVal);
    }

    // On form submit, write full E.164 number to hidden field
    phoneInput.closest('form').addEventListener('submit', function () {
        phoneHidden.value = iti.getNumber(); // e.g. +254712345678
    });
    const courseSelect = document.getElementById('course_id');
    const courseInfo = document.getElementById('courseInfo');
    const infoUnits = document.getElementById('infoUnits');
    const infoQuestions = document.getElementById('infoQuestions');

    // Initialize Tom Select for searchable dropdown
    const tomSelect = new TomSelect(courseSelect, {
        placeholder: 'Type to search courses...',
        allowEmptyOption: true,
        clearButton: true,
        selectOnTab: true,
        openOnFocus: true,
        hideSelected: false,
        highlight: true,
        sortField: {
            field: 'text',
            direction: 'asc'
        },
        render: {
            option: function(data, escape) {
                const option = courseSelect.querySelector(`option[value="${data.value}"]`);
                if (!option || !data.value) return `<div class="py-2 px-1">${escape(data.text)}</div>`;

                const level = option.dataset.level || '';
                const units = option.dataset.units || '0';
                const questions = option.dataset.questions || '0';

                return `<div class="d-flex align-items-start py-2">
                    <div class="flex-grow-1">
                        <div class="fw-semibold mb-1" style="font-size: 0.9rem;">${escape(data.text.split(' - ')[0])}</div>
                        <div class="d-flex align-items-center gap-2">
                            ${level ? `<span class="badge" style="background-color: #dbeafe; color: #1d4ed8; font-size: 0.65rem; font-weight: 600;">${escape(level)}</span>` : ''}
                            <small class="text-muted" style="font-size: 0.75rem;">
                                <i class="bi bi-collection me-1"></i>${escape(units)} Units
                                <span class="mx-1">&bull;</span>
                                <i class="bi bi-question-circle me-1"></i>${escape(questions)} Q&A
                            </small>
                        </div>
                    </div>
                </div>`;
            },
            item: function(data, escape) {
                const option = courseSelect.querySelector(`option[value="${data.value}"]`);
                const level = option ? option.dataset.level : '';
                return `<div class="d-flex align-items-center gap-2">
                    <span>${escape(data.text.split(' - ')[0])}</span>
                    ${level ? `<span class="badge" style="background-color: #dbeafe; color: #1d4ed8; font-size: 0.6rem;">${escape(level)}</span>` : ''}
                </div>`;
            }
        }
    });

    // Focus input when dropdown opens
    tomSelect.on('dropdown_open', function() {
        setTimeout(function() {
            tomSelect.$control_input.focus();
        }, 10);
    });

    // Clear search when item is selected
    tomSelect.on('item_add', function() {
        tomSelect.$control_input.value = '';
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
