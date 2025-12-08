@props(['type' => 'success', 'message' => null, 'title' => null, 'auto' => true])

@php
    // Map Bootstrap types to SweetAlert2 icons
    $iconMap = [
        'success' => 'success',
        'danger' => 'error',
        'error' => 'error',
        'warning' => 'warning',
        'info' => 'info',
        'question' => 'question'
    ];
    $icon = $iconMap[$type] ?? 'info';
    $alertMessage = $message ?? $slot;
@endphp

@if($auto)
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '{{ $icon }}',
                title: '{{ $title ?? ucfirst($icon) }}',
                text: '{{ $alertMessage }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        });
    </script>
    @endpush
@endif
