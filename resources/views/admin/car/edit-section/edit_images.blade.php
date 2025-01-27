<style>
    .image-container {
        display: flex;
        align-items: center;
        justify-content: flex-start;
    }

    .image-preview-wrapper {
        display: flex;
        align-items: center;
    }

    .image-preview-wrapper img {
        margin-right: 10px;
    }

    .image-preview-wrapper button {
        margin-left: 10px;
    }
</style>

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
                alt="profile-image" id="profile_image" class="img-thumbnail img-list" style="width:80;height:100;" >
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
        <span class="text-muted"> resolution: 202 × 120 px</span>
    </div>
    <div class="col-md-4" id="image-preview-detail">
        @if ($car->image_2)
            <img src="{{ file_asset('files-car', $car->image_2) }}"
                alt="profile-image" id="image_2" class="img-thumbnail img-list"  style="width:80;height:80;">
        @endif
    </div>
</div>

<label for="additional-images" class="col-md-2 control-label">Additional Images</label>
<div class="row" style="margin-left:800px;margin-bottom:10px;">
            <button type="button" class="btn btn-primary" id="add-image-section" onclick="addImageSection()">Add Image</button>
        </div>
@include('admin.car.edit-section.images')

<script>
    function previewImage(event, index) {
        let reader = new FileReader();
        reader.onload = function() {
            let preview = document.getElementById("image_preview_" + index);
            preview.src = reader.result;
            document.getElementById("remove-image_" + index).style.display = "block";
            preview.style.display = "block";
        }
        reader.readAsDataURL(event.target.files[0]);
    }


    document.querySelectorAll("button[id^='remove-image_']").forEach(function(button) {
        button.addEventListener("click", function() {
            let index = button.id.split("_")[1];
            let imgremove = document.getElementById("image_removed_" + index);
            if (imgremove) {

                document.getElementById("image_removed_" + index).value = 1;

            }
            let preview = document.getElementById("image_preview_" + index);
            preview.src = "";
            preview.style.display = "none";
            document.getElementById("image_" + index).value = "";
            button.style.display = "none";

        });
    });

</script>
<script>
    let input = document.querySelector('input#image');
    let preview = document.querySelector('#image-preview');
    let removeBtn = document.querySelector('#remove-image');

    input.addEventListener('change', function(event) {
        let file = event.target.files[0];
        let reader = new FileReader();
        reader.onload = function(event) {
            let img = document.createElement('img');
            img.classList.add('img-thumbnail');
            img.classList.add('img-list');
            img.src = event.target.result;
            img.height = 80;
            img.width =80;
            preview.innerHTML = '';
            preview.appendChild(img);
            removeBtn.style.display = 'block';
        }
        reader.readAsDataURL(file);
    });

    removeBtn.addEventListener('click', function() {
        preview.innerHTML = '';
        removeBtn.style.display = 'none';
        input.value = '';

        document.getElementById("is_removed_image").value = 1;

    });
</script>

