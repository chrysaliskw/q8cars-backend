@props([
    'field' => '',
    'fieldName' => '',
])
<div class="form-group">
    <label for="{{ $field }}" class="control-label">{{ $fieldName }}</label>
    <input name="{{ $field }}" {!! $attributes->merge(['class' => 'form-control']) !!}>
    <span class="error" role="alert">
        
        @error($field)
            {{ $message }}</br>
        @enderror
        
        @{{ errors[0] }}
        
    </span>
</div>
