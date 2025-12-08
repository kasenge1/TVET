@props(['title', 'value', 'icon', 'color' => 'primary', 'link' => null, 'prefix' => null])

@php
    $gradientMap = [
        'primary' => 'gradient-primary',
        'success' => 'gradient-success',
        'warning' => 'gradient-warning',
        'danger' => 'gradient-danger',
        'info' => 'gradient-primary'
    ];
    $gradientClass = $gradientMap[$color] ?? 'gradient-primary';

    // Determine value size class based on character length
    $valueLength = strlen((string) $value);
    $sizeClass = '';
    if ($valueLength > 15) {
        $sizeClass = 'stat-value-xs'; // Very large (e.g., KES 1,234,567.89)
    } elseif ($valueLength > 10) {
        $sizeClass = 'stat-value-sm'; // Large (e.g., KES 123,456.00)
    } elseif ($valueLength > 7) {
        $sizeClass = 'stat-value-md'; // Medium (e.g., 1,234,567)
    }
@endphp

<div class="stat-card-modern {{ $gradientClass }} text-white">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="icon-box bg-white bg-opacity-20">
                <i class="bi bi-{{ $icon }}"></i>
            </div>
        </div>
        <div class="mb-2">
            <div class="stat-value fw-bold mb-0 {{ $sizeClass }}">
                @if($prefix)<span class="stat-prefix">{{ $prefix }}</span>@endif{{ $value }}
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-white text-opacity-90 fw-medium">{{ $title }}</div>
            @if($link)
                <a href="{{ $link }}" class="text-white text-opacity-90 text-decoration-none hover-scale">
                    <i class="bi bi-arrow-right-circle fs-5"></i>
                </a>
            @endif
        </div>
    </div>
</div>
