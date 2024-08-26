<div class="row">  <div class="col-md-3">
    <span class="error" role="alert">
                        @error('colors')
                            {{ $message }}</br>
                        @enderror
    </span>  </div>    
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
                    <input class="form-check-input" type="checkbox" name="colors[]" id="colors[]" value="{{ $key }}" {{ $attrCCheck }}>
                        &nbsp;<label class="form-check-label" for="colors" style="color: black;" >
                        {{ $value }} 
                        </label>
                </div>
           
                <div class="form-group" style="padding-top: 10px;">
                    <input type="file" id="colors_image_{{$key}}" name="colors_image_{{$key}}">
                </div> 

                @php 
                    $imageName = get_car_image_by_color($key, $car->id);
                @endphp
                @if ($imageName)
                <div class="col-mod-4" id="image-preview">
                    <img src="{{ file_asset('files-car', $imageName) }}"
                        alt="car-image" id="car_image" class="img-thumbnail" width="100" height="150">
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>