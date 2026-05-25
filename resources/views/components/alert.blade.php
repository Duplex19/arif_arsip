@props(['type' => 'success', 'message'])

@php
    $colors = [
        'success' => 'bg-emerald-500/10 border-emerald-500/30 text-emerald-600',
        'error' => 'bg-red-500/10 border-red-500/30 text-red-600',
        'warning' => 'bg-amber-500/10 border-amber-500/30 text-amber-600',
        'info' => 'bg-indigo-500/10 border-indigo-500/30 text-indigo-600',
    ];
    $darkColors = [
        'success' => 'dark:text-emerald-300',
        'error' => 'dark:text-red-300',
        'warning' => 'dark:text-amber-300',
        'info' => 'dark:text-indigo-300',
    ];
    $color = $colors[$type] ?? $colors['info'];
    $darkColor = $darkColors[$type] ?? '';
@endphp

<div {{ $attributes->merge(['class' => "{$color} {$darkColor} border rounded-xl px-4 py-3 mb-4 text-sm flex items-center gap-3 glass animate-fade-in"]) }}>
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        @if($type === 'success')
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        @elseif($type === 'error')
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        @else
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        @endif
    </svg>
    <span class="flex-1 font-medium">{{ $message }}</span>
    <button onclick="this.parentElement.remove()" class="opacity-60 hover:opacity-100 transition-opacity">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</div>
