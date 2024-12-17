<div id="image-section-container">
    @php
    $carImagesCount = count($carImages);
@endphp
    @for($index = 0; $index < $carImagesCount; $index++)
        @php
            $carImage = $carImages[$index];
        @endphp

        <div class="form-group row" id="image-section-row-{{ $index }}">
        <div class="col-md-3">
            <div class="form-group">
                <select name="img_section_{{$index}}" class="form-control">
                @foreach (config('params.car.image-section') as $value => $label)
                            <option value="{{ $value }}"
                                @if($value == $carImage->section) selected @endif>
                                {{ $label }}
                            </option>
                            @endforeach
                </select>
                @error('img_section_' . ($index))
                    <span class="error" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-3">
            <input id="image_{{ $index}}" type="file" name="image_{{$index}}" class="form-control"
                onchange="previewImage(event, {{$index}})" />
                <input type="hidden" id="image_old_{{ $index }}" name="image_old_{{ $index }}"
                value="{{ $carImage->file_name }}">

            <span class="error" role="alert">
                @error('image_' . ($index))
                    {{ $message }}</br>
                @enderror
            </span>
        </div>
           

            <div class="col-md">
                <div class="form-group">
                @if ($carImage->file_name)
                            <div class="image-preview-wrapper">
                                <img src="{{ file_asset('files-car', $carImage->file_name) }}" alt="profile-image"
                                     id="image_preview_{{ $index }}" class="img-thumbnail img-list">
                                <button id="remove-image_{{ $index }}" type="button" class="btn btn-danger">
                                    x
                                </button>
                            </div>
                            <input type="hidden" id="image_removed_{{ $index }}" name="image_removed_{{ $index }}"
                                value="0">
                            <input type="hidden" id="deleted_image_id_{{ $index }}" name="deleted_image_id_{{ $index }}"
                                value="{{ $carImage->id }}">
                        @else
                            <div class="image-preview-wrapper">
                                <img src="" alt="" id="image_preview_{{ $index }}" class="img-thumbnail img-list"
                                     width="100" height="150" style="display:none;">
                                <button id="remove-image_{{ $index }}" type="button" class="btn btn-danger" style="display:none">
                                    x
                                </button>
                            </div>
                        @endif
                </div>
            </div>

           
        </div>
    @endfor
</div>

<script>
    let imageSectionCount = {{ $carImagesCount }};

    // Function to add a new image section
    function addImageSection() {
        const container = document.getElementById('image-section-container');

         const newSection = `
            <div class="form-group row" id="image-section-row-${imageSectionCount}">
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="img_section_${imageSectionCount}" class="form-control">
                            @foreach (config('params.car.image-section') as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <input id="image_${imageSectionCount}" type="file" name="image_${imageSectionCount}" class="form-control"
                        onchange="previewImage(event, ${imageSectionCount})" />
                    <input type="hidden" id="image_old_${imageSectionCount}" name="image_old_${imageSectionCount}" value="">
                </div>
                <div style="display:flex;">
                    <div class="col-md">
                        <img src="" alt="" id="image_preview_${imageSectionCount}" class="img-thumbnail img-list"
                            width="100" height="150" style="display:none;">
                    </div>
                    <div class="col-md">
                        <button id="remove-image_${imageSectionCount}" type="button" class="btn btn-danger"
                            onclick="removeImageRow(${imageSectionCount})">
                             x
                        </button>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', newSection);
        imageSectionCount++;
    }

    // Function to preview image
    function previewImage(event, key) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById(`image_preview_${key}`);
            output.src = reader.result;
            output.style.display = 'block';

            const removeButton = document.getElementById(`remove-image_${key}`);
            removeButton.style.display = 'inline-block';
        };
        reader.readAsDataURL(event.target.files[0]);

        // Show file input only after selecting an image
        const fileInput = document.getElementById(`image_${key}`);
        fileInput.style.display = 'none'; // Hide file input once the image is uploaded
    }

    // Function to remove image row
    function removeImageRow(key) {
        const row = document.getElementById(`image-section-row-${key}`);
        row.remove();
    }

    // Function to handle edit image (show image in preview for editing)
    function editImage(key) {
        const fileInput = document.getElementById(`image_${key}`);
        fileInput.style.display = 'block'; // Show file input when editing
        fileInput.click(); // Trigger the file input
    }
</script>
