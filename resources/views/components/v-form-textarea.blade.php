@props([
    'field' => '',
    'fieldName' => '',
    'fieldValue' => ''
])
<div class="form-group">
    <label class="control-label" for="{{ $field }}">{{ $fieldName }}</label>
    <textarea name="{{ $field }}" {!! $attributes->merge(['class' => 'form-control']) !!}>{{ $fieldValue }}</textarea>
    <span class="error" role="alert">
        
        @error($field)
            {{ $message }}</br>
        @enderror
        
        @{{ errors[0] }}
        
    </span>
</div>