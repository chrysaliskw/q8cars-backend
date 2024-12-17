<div id="image-section-container" class="container">
    @php
        $carImagesCount = count($carImages);
    @endphp
   
    <div class="row" id="image-section-row">
        @for ($index = 0; $index < $carImagesCount; $index++)
            @php
                $carImage = $carImages[$index];
            @endphp
            <div class="col-md-4 mb-4" id="image-section-row-{{ $index }}">
                <div class="form-group">
                    <!-- Dropdown -->
                    <select name="img_section_{{ $index }}" class="form-control mb-2">
                        @foreach (config('params.car.image-section') as $value => $label)
                            <option value="{{ $value }}" @if ($value == $carImage->section) selected @endif>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <!-- File Input -->
                    <input id="image_{{ $index }}" type="file" name="image_{{ $index }}" class="form-control mb-2"
                           onchange="previewImage(event, {{ $index }})" />
                    <input type="hidden" id="image_old_{{ $index }}" name="image_old_{{ $index }}"
                           value="{{ $carImage->file_name }}">

                    <!-- Image Preview -->
                    <div class="image-preview-wrapper text-center">
                        @if ($carImage->file_name)
                            <img src="{{ file_asset('files-car', $carImage->file_name) }}" alt="profile-image"
                                 id="image_preview_{{ $index }}" class="img-thumbnail img-list"
                                 style="width: 100px; height: 80px; object-fit: cover;">
                        @else
                            <img src="" alt="" id="image_preview_{{ $index }}" class="img-thumbnail img-list"
                                 style="width: 100px; height: 80px; object-fit: cover; display: none;">
                        @endif
                        <button id="remove-image_{{ $index }}" type="button" class="btn btn-danger mt-2"
                                onclick="removeImageRow({{ $index }})">
                            x
                        </button>
                    </div>
                </div>
            </div>
        @endfor
    </div>


</div>

<script>
let imageSectionCount = {{ $carImagesCount }};

// Function to add a new image section
function addImageSection() {
    const container = document.querySelector("#image-section-container .row");

    const newSection = `
        <div class="col-md-4 mb-4" id="image-section-row-${imageSectionCount}">
            <div class="form-group">
                <!-- Dropdown -->
                <select name="img_section_${imageSectionCount}" class="form-control mb-2">
                    @foreach (config('params.car.image-section') as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>

                <!-- File Input -->
                <input id="image_${imageSectionCount}" type="file" name="image_${imageSectionCount}" class="form-control mb-2"
                       onchange="previewImage(event, ${imageSectionCount})" />

                <!-- Image Preview -->
                <div class="image-preview-wrapper text-center">
                    <img src="" alt="" id="image_preview_${imageSectionCount}" class="img-thumbnail img-list"
                         style="width: 100px; height: 80px; object-fit: cover; display: none;">
                    <button id="remove-image_${imageSectionCount}" type="button" class="btn btn-danger mt-2"
                            onclick="removeImageRow(${imageSectionCount})">
                        x
                    </button>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML("beforeend", newSection);
    imageSectionCount++;
}

// Function to preview image
function previewImage(event, key) {
    const reader = new FileReader();
    reader.onload = function () {
        const output = document.getElementById(`image_preview_${key}`);
        output.src = reader.result;
        output.style.display = "block";
    };
    reader.readAsDataURL(event.target.files[0]);
}

// Function to remove image row
function removeImageRow(key) {
    const row = document.getElementById(`image-section-row-${key}`);
    row.remove();
}
</script>

