@props([
    'field' => '',
    'fieldName' => '',
])
<div class="form-group">
    <div class="form-check text-center">
        <input type="checkbox" name="{{ $field }}" id="{{ $field }}" {!! $attributes->merge(['class' => 'form-check-input']) !!}>
        <label for="{{ $field }}" class="form-check-label">{{ $fieldName }}</label>
    </div>
    @error($field)
        <span class="error" role="alert">{{ $message }}</span>
    @enderror
</div>
{{-- usage
    <x-form-checkbox field="terms" fieldName="Accept Terms and Conditions" />
--}}
