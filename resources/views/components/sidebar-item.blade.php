@props(['href', 'icon', 'active' => false])

<li class="nav-item">
    <a href="{{ $href }}" class="nav-link {{ $active ? 'active' : '' }}">
        <i class="bi bi-{{ $icon }} me-2"></i>
        {{ $slot }}
    </a>
</li>
