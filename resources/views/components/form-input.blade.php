@props([
    'field' => '',
    'fieldName' => '',
])
<div class="form-group">
    <label for="{{ $field }}" class="control-label">{{ $fieldName }}</label>
    <input name="{{ $field }}" {!! $attributes->merge(['class' => 'form-control']) !!}>
    @error($field)
        <span class="error" role="alert">{{ $message }}</span>
    @enderror
</div>
