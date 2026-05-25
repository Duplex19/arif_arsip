@props(['id', 'title' => '', 'size' => 'md'])

@php
    $sizes = ['sm' => 'max-w-sm', 'md' => 'max-w-lg', 'lg' => 'max-w-2xl', 'xl' => 'max-w-4xl'];
    $maxWidth = $sizes[$size] ?? 'max-w-lg';
@endphp

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="glass-card w-full {{ $maxWidth }} transform transition-all">
            @if($title)
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">{{ $title }}</h3>
                    <button type="button" onclick="document.getElementById('{{ $id }}').classList.add('hidden')" class="glass-button p-1 text-slate-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif
            {{ $slot }}
        </div>
    </div>
</div>
