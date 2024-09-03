<div class="form-group row mb-5">
    <label for="image" class="col-md-2 control-label">Profile Image 1*</label>
    <div class="col-md-5">
        <input id="image" type="file" name="image" class="form-control">
        <span class="error" role="alert">
            @error('image')
                {{ $message }}</br>
            @enderror
        </span>
    </div>
    <div class="col-md-4" id="image-preview">
        @if ($car->image)
            <img src="{{ file_asset('files-car', $car->image) }}"
                alt="profile-image" id="profile_image" class="img-thumbnail" width="100" height="150">
        @endif
    </div>
</div>

<div class="form-group row mb-5">
    <label for="image_detail" class="col-md-2 control-label">Profile Image 2*</label>
    <div class="col-md-5">
        <input id="image_detail" type="file" name="image_detail" class="form-control">
        <span class="error" role="alert">
            @error('image_detail')
                {{ $message }}</br>
            @enderror
        </span>
    </div>
    <div class="col-md-4" id="image-preview-detail">
        @if ($car->image_2)
            <img src="{{ file_asset('files-car', $car->image_2) }}"
                alt="profile-image" id="image_2" class="img-thumbnail" width="100" height="150">
        @endif
    </div>
</div>

<label for="additional-images" class="col-md-2 control-label">
    Additional Images      
</label>

@php
    $carImagesCount = count($carImages);
    $rows = ceil($carImagesCount / 3); // Calculate the number of rows needed
@endphp

@for($row = 0; $row < $rows; $row++)
    <div class="form-group row">
        @for($col = 0; $col < 3; $col++)
            @php
                $index = ($row * 3) + $col;
                $carImage = $carImages[$index] ?? null;
            @endphp
            
            @if($carImage)
                <div class="col-md-4">
                    <div class="form-group">
                        <select name="img_section_{{ $index }}" class="form-control">
                            @foreach (config('params.car.image-section') as $value => $label)
                                <option value="{{ $value }}" 
                                    @if($value == $carImage->section) selected @endif>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('img_section_' . $index)
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input id="image_{{ $index }}" type="file" name="image_{{ $index }}" class="form-control"
                            onchange="previewImage(event, {{ $index }})">
                        <input type="hidden" id="image_old_{{ $index }}" name="image_old_{{ $index }}"
                            value="{{ $carImage->file_name }}">
                        <span class="error" role="alert">
                            @error('image_' . $index)
                                {{ $message }}</br>
                            @enderror
                        </span>
                    </div>
                    <div class="form-group">
                        @if ($carImage->file_name)
                            <img src="{{ file_asset('files-car', $carImage->file_name) }}" alt="profile-image"
                                id="image_preview_{{ $index }}" class="img-thumbnail" width="100" height="150">
                        @else
                            <img src="" alt="" id="image_preview_{{ $index }}" class="img-thumbnail"
                                width="100" height="150" style="display:none;">
                        @endif
                    </div>
                    <div class="form-group">
                        @if ($carImage->file_name)
                            <button id="remove-image_{{ $index }}" type="button" class="btn btn-danger">
                                x
                            </button>
                            <input type="hidden" id="image_removed_{{ $index }}" name="image_removed_{{ $index }}"
                                value="0">
                            <input type="hidden" id="deleted_image_id_{{ $index }}" name="deleted_image_id_{{ $index }}"
                                value="{{ $carImage->id }}">
                        @else
                            <button id="remove-image_{{ $index }}" type="button" class="btn btn-danger" style="display:none">
                                x
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        @endfor
    </div>
@endfor


<script>
   function previewImage(event, index)
   {
        let reader = new FileReader();
        reader.onload = function() {
            let preview = document.getElementById("image_preview_" + index);
            preview.src = reader.result;
            preview.style.display = "block";
            document.getElementById("remove-image_" + index).style.display = "block";
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    document.querySelectorAll("button[id^='remove-image_']").forEach(function(button) {
        button.addEventListener("click", function() {
            let index = button.id.split("_")[2];
            document.getElementById("image_removed_" + index).value = 1;
            let preview = document.getElementById("image_preview_" + index);
            preview.src = "";
            preview.style.display = "none";
            document.getElementById("image_" + index).value = "";
            button.style.display = "none";
        });
    });

    let input1 = document.querySelector('input#image');
    let preview1 = document.querySelector('#image-preview');
    let removeBtn1 = document.querySelector('#remove-image');

    input1.addEventListener('change', function(event) {
        let file = event.target.files[0];
        let reader = new FileReader();
        reader.onload = function(event) {
            let img = document.createElement('img');
            img.classList.add('img-thumbnail');
            img.src = event.target.result;
            img.width = 100;
            img.height = 150;
            preview1.innerHTML = '';
            preview1.appendChild(img);
            removeBtn1.style.display = 'block';
        }
        reader.readAsDataURL(file);
    });

    removeBtn1.addEventListener('click', function() {
        preview1.innerHTML = '';
        removeBtn1.style.display = 'none';
        input1.value = '';
        document.getElementById("is_removed_image").value = 1;
    });

    let input2 = document.querySelector('input#image_detail');
    let preview2 = document.querySelector('#image-preview-detail');
    let removeBtn2 = document.querySelector('#remove-image-detail');

    input2.addEventListener('change', function(event) {
        let file = event.target.files[0];
        let reader = new FileReader();
        reader.onload = function(event) {
            let img = document.createElement('img');
            img.classList.add('img-thumbnail');
            img.src = event.target.result;
            img.width = 100;
            img.height = 150;
            preview2.innerHTML = '';
            preview2.appendChild(img);
            removeBtn2.style.display = 'block';
        }
        reader.readAsDataURL(file);
    });

    removeBtn2.addEventListener('click', function() {
        preview2.innerHTML = '';
        removeBtn2.style.display = 'none';
        input2.value = '';
        document.getElementById("is_removed_image_detail").value = 1;
    });

</script>
