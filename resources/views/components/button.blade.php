@props(['type' => 'button', 'variant' => 'primary', 'size' => null, 'icon' => null])

<button 
    type="{{ $type }}" 
    {{ $attributes->merge(['class' => 'btn btn-' . $variant . ($size ? ' btn-' . $size : '')]) }}
>
    @if($icon)
        <i class="bi bi-{{ $icon }}"></i>
    @endif
    {{ $slot }}
</button>
