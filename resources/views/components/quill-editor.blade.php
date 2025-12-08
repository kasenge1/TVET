@props([
    'name' => 'content',
    'id' => null,
    'value' => '',
    'placeholder' => 'Enter text here...',
    'height' => '300px',
    'required' => false,
    'label' => null,
    'error' => null
])

@php
    $editorId = $id ?? 'editor_' . $name;
    $textareaId = 'textarea_' . $name;
@endphp

@if($label)
<div class="mb-4">
    <label for="{{ $textareaId }}" class="form-label fw-medium">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
        <small class="text-muted">(Supports rich formatting & formulas)</small>
    </label>
@endif
    <div id="{{ $editorId }}" style="height: {{ $height }};"></div>
    <textarea id="{{ $textareaId }}"
              name="{{ $name }}"
              class="d-none @error($name) is-invalid @enderror"
              {{ $required ? 'required' : '' }}>{{ old($name, $value) }}</textarea>
    @if($error || $errors->has($name))
        <div class="invalid-feedback d-block">{{ $error ?? $errors->first($name) }}</div>
    @endif
@if($label)
</div>
@endif

@once
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
<style>
    .ql-editor {
        min-height: 250px;
        font-size: 15px;
    }
    .ql-toolbar {
        background: #f8fafc;
        border-radius: 8px 8px 0 0;
        flex-wrap: wrap;
    }
    .ql-container {
        border-radius: 0 0 8px 8px;
        font-family: Inter, sans-serif;
    }

    /* Formula tooltip/popup fix - make it responsive and visible */
    .ql-tooltip {
        z-index: 9999 !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        max-width: 90vw !important;
        white-space: normal !important;
    }

    .ql-tooltip input[type="text"] {
        width: 100% !important;
        min-width: 200px;
        max-width: 400px;
    }

    .ql-tooltip.ql-editing {
        left: 50% !important;
        transform: translateX(-50%) !important;
    }

    /* Ensure formula input is visible on mobile */
    @media (max-width: 768px) {
        .ql-tooltip {
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            width: 90% !important;
            max-width: 350px !important;
            background: white;
            border: 1px solid #ccc;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            padding: 15px !important;
            border-radius: 8px;
        }

        .ql-tooltip input[type="text"] {
            width: 100% !important;
            padding: 10px !important;
            font-size: 16px !important;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .ql-tooltip a {
            display: inline-block;
            padding: 8px 16px;
            margin: 5px;
            background: #007bff;
            color: white !important;
            border-radius: 4px;
            text-decoration: none;
        }

        .ql-tooltip a.ql-remove {
            background: #dc3545;
        }

        .ql-tooltip::before {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
    }

    /* Toolbar responsive wrapping */
    .ql-toolbar.ql-snow {
        display: flex;
        flex-wrap: wrap;
        padding: 8px;
    }

    .ql-toolbar.ql-snow .ql-formats {
        margin-right: 10px;
        margin-bottom: 5px;
    }

    /* Ensure toolbar buttons are touchable on mobile */
    @media (max-width: 768px) {
        .ql-toolbar.ql-snow button {
            width: 32px;
            height: 32px;
        }

        .ql-toolbar.ql-snow .ql-picker {
            height: 32px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
@endpush
@endonce

@push('scripts')
<script>
(function() {
    const textarea = document.getElementById('{{ $textareaId }}');

    // Initialize Quill editor and expose globally
    const editorInstance = new Quill('#{{ $editorId }}', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'align': [] }],
                ['blockquote', 'code-block'],
                ['link', 'image', 'video'],
                ['formula'],
                ['clean']
            ]
        },
        placeholder: '{{ $placeholder }}'
    });

    // Expose editor instance globally for potential external access
    window.editor_{{ str_replace('-', '_', $editorId) }} = editorInstance;

    // Set initial content from textarea if exists
    if (textarea.value) {
        editorInstance.root.innerHTML = textarea.value;
    }

    // Sync editor content to hidden textarea on every change
    editorInstance.on('text-change', function() {
        const html = editorInstance.root.innerHTML;
        // Set empty string if only contains empty paragraph
        textarea.value = (html === '<p><br></p>' || html === '<p></p>') ? '' : html;
    });

    // Also sync on form submit to ensure latest content
    const form = textarea.closest('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const html = editorInstance.root.innerHTML;
            textarea.value = (html === '<p><br></p>' || html === '<p></p>') ? '' : html;
        });
    }
})();
</script>
@endpush
