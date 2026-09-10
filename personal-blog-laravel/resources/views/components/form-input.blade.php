@props(['name', 'label', 'type' => 'text', 'value' => '', 'placeholder' => ''])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @if ($type === 'textarea')
        <textarea class="form-control border-dark @error($name) is-invalid @enderror" id="{{ $name }}"
            name="{{ $name }}" rows="10" placeholder="{{ $placeholder }}">{{ old($name, $value) }}</textarea>
    @else
        <input type="{{ $type }}" class="form-control border-dark @error($name) is-invalid @enderror"
            id="{{ $name }}" name="{{ $name }}" placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}" />
    @endif

    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
