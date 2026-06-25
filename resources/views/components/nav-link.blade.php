@props(['href', 'active' => false, 'icon' => ''])

@php
$base    = 'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150';
$activeClass  = 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
$inactiveClass = 'text-gray-400 hover:bg-gray-800 hover:text-gray-100';
$classes = $base . ' ' . ($active ? $activeClass : $inactiveClass);
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {!! $icon !!}
    </svg>
    @endif
    <span>{{ $slot }}</span>
</a>
