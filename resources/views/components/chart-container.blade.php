@props(['title' => ''])

<div {{ $attributes->merge(['class' => 'glass-card']) }}>
    @if($title)
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-semibold text-theme-primary">{{ $title }}</h3>
        </div>
    @endif
    <div style="min-height: 300px;">
        {{ $slot }}
    </div>
</div>
