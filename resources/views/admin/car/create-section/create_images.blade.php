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
    <div class="col-mod-4" id="image-preview">
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
    <div class="col-mod-4" id="image-preview-2"></div>
</div>

<label for="image" class="col-md-2 control-label">
    Additional Images
</label>
<div class="row" style="margin-left:800px">
            <button type="button" class="btn btn-primary" id="add-image-section" onclick="addImageSection()">Add Image</button>
        </div>
{{--@for($key = 1; $key <= 20; $key++)
    <div class="form-group row">


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
            <input type="hidden" id="image_old_{{$key}}" name="image_old_{{$key}}"
                value="">

            <span class="error" role="alert">
                @error('image_' . ($key))
                    {{ $message }}</br>
                @enderror
            </span>
        </div>
        <div style="display:flex;">
            <div class="col-md">
                <img src="" alt="" id="image_preview_{{ $key}}" class="img-thumbnail"
                                width="100" height="150" style="display:none;">
            </div>
            <div class="col-md">
                    <button id="remove-image_{{ $key}}" type="button" class="btn btn-danger"
                        style="display:none">
                         x
                </button>
            </div>
        </div>

    </div>
@endfor--}}

@include('admin.car.create-section.images')

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

    let input = document.querySelector('input#image');
    let preview = document.querySelector('#image-preview');
    let removeBtn = document.createElement('button');
    removeBtn.id = 'remove-image';
    removeBtn.type = 'button';
    removeBtn.className = 'btn btn-danger';
    removeBtn.style.display = 'none';
    removeBtn.innerText = 'x';
    preview.appendChild(removeBtn);

    input.addEventListener('change', function(event) {
        let file = event.target.files[0];
        let reader = new FileReader();
        reader.onload = function(event) {
            let img = document.createElement('img');
            img.classList.add('img-thumbnail');
            img.classList.add('img-list');
            img.src = event.target.result;
            // img.height = 80;
            // img.width =100;
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
    });

    let inputDetail = document.querySelector('input#image_detail');
    let previewDetail = document.querySelector('#image-preview-2');
    let removeBtnDetail = document.createElement('button');
    removeBtnDetail.id = 'remove-image_detail';
    removeBtnDetail.type = 'button';
    removeBtnDetail.className = 'btn btn-danger';
    removeBtnDetail.style.display = 'none';
    removeBtnDetail.innerText = 'x';
    previewDetail.appendChild(removeBtnDetail);

    inputDetail.addEventListener('change', function(event) {
        let file = event.target.files[0];
        let reader = new FileReader();
        reader.onload = function(event) {
            let img = document.createElement('img');
            img.classList.add('img-thumbnail');
            img.classList.add('img-list');
            img.src = event.target.result;
            // img.width = 100;
            // img.height = 100;
            previewDetail.innerHTML = '';
            previewDetail.appendChild(img);
            removeBtnDetail.style.display = 'block';
        }
        reader.readAsDataURL(file);
    });

    removeBtnDetail.addEventListener('click', function() {
        previewDetail.innerHTML = '';
        removeBtnDetail.style.display = 'none';
        inputDetail.value = '';
    });

    document.querySelectorAll("button[id^='remove-image_']").forEach(function(button) {
        button.addEventListener("click", function() {
            let index = button.id.split("_")[1];
            let preview = document.getElementById("image_preview_" + index);
            preview.src = "";
            preview.style.display = "none";
            document.getElementById("image_" + index).value = "";
            button.style.display = "none";
        });
    });
</script>


{{-- <script>
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
            // img.width = 100;
            // img.height = 150;
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
</script> --}}
