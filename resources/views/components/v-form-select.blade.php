@props([
    'field' => '',
    'fieldName' => '',
    'data' => [],
    'defaultPrompt' => '',
    'errorId' => ''
])

<div class="form-group">
    <label for="{{ $field }}" class="control-label">{{ $fieldName }}</label>
    <select name="{{ $field }}" {!! $attributes->merge(['class' => 'form-control']) !!} >
        <option value=''> {{ $defaultPrompt }} </option>
        {{ $slot }}
    </select>
    <span class="error" role="alert" id="{{ $errorId }}">
        
        @error($field)
            {{ $message }}</br>
        @enderror
        
        @{{ errors[0] }}
        
    </span>
</div>
