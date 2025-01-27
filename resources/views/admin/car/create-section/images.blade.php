<div id="image-section-container">
    @for($key = 1; $key <= 1; $key++) <!-- Start with one visible input -->
    <div class="form-group row" id="image-section-row-{{$key}}">
        <div class="col-md-3">
            <div class="form-group">
                <select name="img_section_{{$key}}" class="form-control">
                    @foreach (config('params.car.image-section') as $value => $label)
                    <option value="{{ $value }}">
                        {{ $label }}</option>
                    @endforeach
                </select>
                @error('img_section_' . ($key))
                    <span class="error" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-3">
            <input id="image_{{ $key}}" type="file" name="image_{{$key}}" class="form-control"
                onchange="previewImage(event, {{$key}})" />
                <span class="text-muted"> resolution:395 × 322 px</span>        
            <input type="hidden" id="image_old_{{$key}}" name="image_old_{{$key}}" value="">

            <span class="error" role="alert">
                @error('image_' . ($key))
                    {{ $message }}</br>
                @enderror
            </span>
        </div>
        <div style="display:flex;">
            <div class="col-md">
                <img src="" alt="" id="image_preview_{{ $key}}" class="img-thumbnail img-list"
                style="width: 100px; height: 80px;display:none; ">
            </div>
            <div class="col-md">
                <button id="remove-image_{{ $key}}" type="button" class="btn btn-danger"
                    style="display:none" onclick="removeImageRow({{$key}})">
                     x
                </button>
            </div>
        </div>
    </div>
    @endfor
</div>

<script>
    let imageSectionCount = 1;

    function addImageSection() {
        imageSectionCount++;
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
                       <span class="text-muted"> resolution:395 × 322 px</span>        
                </div>
                <div style="display:flex;">
                    <div class="col-md">
                        <img src="" alt="" id="image_preview_${imageSectionCount}" class="img-thumbnail img-list"
                            style="width: 100px; height: 80px; object-fit: cover;display:none;">
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
    }

    // function previewImage(event, key) {
    //     const reader = new FileReader();
    //     reader.onload = function () {
    //         const output = document.getElementById(`image_preview_${key}`);
    //         output.src = reader.result;
    //         output.style.display = 'block';
    //     };
    //     reader.readAsDataURL(event.target.files[0]);

    //     const removeButton = document.getElementById(`remove-image_${key}`);
    //     removeButton.style.display = 'inline-block';
    // }

    function removeImageRow(key) {
        const row = document.getElementById(`image-section-row-${key}`);
        row.remove();
    }
</script>
