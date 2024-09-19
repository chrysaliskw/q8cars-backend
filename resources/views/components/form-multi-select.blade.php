@props([
    'field' => '',
    'fieldName' => '',
    'data' => [], // Array of options in the format ['value' => 'label'], multiselect wwith search
    'defaultPrompt' => ''
])

<div class="form-group">
    <label for="{{ $field }}" class="control-label">{{ $fieldName }}</label>
    <select name="{{ $field }}[]" id="{{ $field }}" {!! $attributes->merge(['class' => 'form-control']) !!} multiple>
            <option value="" >{{ $defaultPrompt }}</option>
            {{ $slot }}
    </select>
    @error($field)
        <span class="error" role="alert">{{ $message }}</span>
    @enderror
</div>

<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Include Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#{{ $field }}').select2({
        placeholder: '{{ $defaultPrompt }}',
        allowClear: false
    });
});
</script>

{{--
    usage 
    <x-form-multi-select field="user" field-name="User" defaultPrompt="Select User" id="userSelect">
        @foreach($userArr as $key => $value)
            <option {{ old("user") == $key ? "selected" : "" }} value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </x-form--multi-select>--}}
