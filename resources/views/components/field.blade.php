@props(['name', 'label', 'type' => 'text', 'options' => null, 'hint' => null, 'value' => null, 'required' => true])
@php
    $key = str_replace(['[', ']'], ['.', ''], $name); // jadwal[0][tahap] -> jadwal.0.tahap
    $val = old($key, $value);
@endphp
<div {{ $attributes->only('class') }}>
    <label for="{{ $name }}" class="label">{{ $label }}@unless ($required) <span class="font-normal text-slate-400">(opsional)</span>@endunless</label>

    @if ($options)
        <select id="{{ $name }}" name="{{ $name }}" @required($required) {{ $attributes->except('class')->class(['input', '!border-rose-400' => $errors->has($key)]) }}>
            <option value="">Pilih…</option>
            @foreach ($options as $optValue => $text)
                <option value="{{ $optValue }}" @selected((string) $val === (string) $optValue)>{{ $text }}</option>
            @endforeach
        </select>
    @elseif ($type === 'textarea')
        <textarea id="{{ $name }}" name="{{ $name }}" rows="3" @required($required) {{ $attributes->except('class')->class(['input', '!border-rose-400' => $errors->has($key)]) }}>{{ $val }}</textarea>
    @else
        <input id="{{ $name }}" type="{{ $type }}" name="{{ $name }}" value="{{ $type === 'password' ? '' : $val }}" @required($required)
            {{ $attributes->except('class')->class(['input', '!border-rose-400' => $errors->has($key)]) }}>
    @endif

    @if ($hint)
        <p class="mt-1 text-xs text-slate-500">{{ $hint }}</p>
    @endif
    @error($key)
        <p class="error">{{ $message }}</p>
    @enderror
</div>
