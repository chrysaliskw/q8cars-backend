<div class="row">
    <div class="col-md-3">
        <span class="error" role="alert">
            @error('colors')
                {{ $message }}</br>
            @enderror
        </span>
    </div>    
</div>

<div id="color-options" class="row">
    @foreach (config('params.colors') as $key => $value)
    <div class="col-md-4">
        <div class="card border-primary" style="background-color: #f0f8ff;">
            <div class="card-body">
                <div class="form-check form-check-inline col-md-12">
                    <input class="form-check-input" type="checkbox" name="colors[]" id="colors{{ $key }}" 
                        value="{{ $key }}" {{ in_array($key, old('colors', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="colors{{ $key }}" style="color: black;">
                        {{ $value }}
                    </label>
                </div>
                <div class="form-group" style="padding-top: 10px;">
                    <input type="file" id="colors_image_{{ $key }}" name="colors_image_{{ $key }}">
                </div>
                <small>Image required when color is selected</small>
            </div>
        </div>
    </div>
    @endforeach
</div>
