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
                    <!-- Custom file input -->
                    <label class="custom-file-upload" for="colors_image_{{ $key }}">
                        <img src="{{ asset('images/upload.svg') }}" />|
                    </label>
                    <input type="file" id="colors_image_{{ $key }}" name="colors_image_{{ $key }}" 
                        class="file-input d-none" onchange="displayFileName(this, 'file-name-{{ $key }}')">

                    <!-- Display chosen file name -->
                    <span id="file-name-{{ $key }}" class="file-name-display">No file chosen</span>

                    <!-- Display validation error for the color image -->
                    @if($errors->has('colors_image_' . $key))
                        <span class="error">
                            {{ $errors->first('colors_image_' . $key) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
