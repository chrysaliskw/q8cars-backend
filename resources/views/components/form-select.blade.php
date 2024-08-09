@props([
    'field' => '',
    'fieldName' => '',
    'data' => [],
    'defaultPrompt' => ''
])

<div class="form-group">
    <label for="{{ $field }}" class="control-label">{{ $fieldName }}</label>
    <select name="{{ $field }}" {!! $attributes->merge(['class' => 'form-control']) !!} class="form-control">
        <option value=''> {{ $defaultPrompt }} </option>
        {{ $slot }}
    </select>
    @error($field)
        <span class="error" role="alert">{{ $message }}</span>
    @enderror
</div>
