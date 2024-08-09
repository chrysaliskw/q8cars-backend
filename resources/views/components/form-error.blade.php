@props([
    'field' => '',
])

<span class="error" role="alert">
    @error($field)
        {{ $message }}
    @enderror
</span>