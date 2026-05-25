@props(['label', 'name', 'type' => 'text', 'value' => '', 'required' => false, 'disabled' => false, 'options' => [], 'placeholder' => ''])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-300 mb-1">
            {{ $label }}
            @if($required) <span class="text-red-400">*</span> @endif
        </label>
    @endif

    @if($type === 'select')
        <select name="{{ $name }}" id="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge(['class' => 'glass-input text-white']) }}>
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach($options as $key => $option)
                <option value="{{ $key }}" {{ (string)$value === (string)$key ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endforeach
        </select>
    @elseif($type === 'textarea')
        <textarea name="{{ $name }}" id="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge(['class' => 'glass-input text-white']) }}>{{ old($name, $value) }}</textarea>
    @else
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
            value="{{ old($name, $value) }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge(['class' => 'glass-input text-white']) }}>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
    @enderror
</div>
