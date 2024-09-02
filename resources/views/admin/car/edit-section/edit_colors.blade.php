<div class="row">
    <div class="col-md-3">
        <span class="error" role="alert">
            @error('colors')
                {{ $message }}<br>
            @enderror
        </span>
    </div>    
</div>
<div class="row">
    @foreach (config('params.colors') as $key => $value)
        @php
            $attrCCheck = in_array($key, $currentColors) ? 'checked' : '';
        @endphp
    <div class="col-md-3">
        <div class="card border-primary" style="background-color: #f0f8ff;">
            <div class="card-body">
                <div class="form-check form-check-inline col-md-12">
                    <input class="form-check-input" type="checkbox" name="colors[]" id="colors_{{ $key }}" value="{{ $key }}" {{ $attrCCheck }}>
                    <label class="form-check-label" for="colors_{{ $key }}" style="color: black;">
                        {{ $value }}
                    </label>
                    <input type="hidden" name="colors_image_old_{{ $key }}" value="{{ get_car_image_by_color($key, $car->id) }}">
                </div>

                <div class="form-group" style="padding-top: 10px;">
                    <input type="file" id="colors_image_{{ $key }}" name="colors_image_{{ $key }}">
                </div>

                <div class="form-group">
                    @php 
                        $imageName = get_car_image_by_color($key, $car->id);
                    @endphp
                    @if ($imageName)
                    <div class="col-mod-4">
                        <img src="{{ file_asset('files-car', $imageName) }}"
                             alt="car-image" id="image-preview_{{ $key }}" class="img-thumbnail" width="100" height="150">
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>