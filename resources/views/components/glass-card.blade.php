<div {{ $attributes->merge(['class' => 'glass-card']) }}>
    @if(isset($header))
        <div class="mb-4">
            {{ $header }}
        </div>
    @endif
    {{ $slot }}
</div>
