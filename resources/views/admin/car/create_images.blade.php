<div class="form-group row mb-5">
    <label for="image" class="col-md-2 control-label">Profile Image</label>
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
@for($key = 0; $key < 20; $key++)
    <div class="form-group row">
        <label for="image" class="col-md-2 control-label">
            @if ($key == 0)
                Additional Images
            @endif
        </label>
        
        <div class="col-md-3">
            <div class="form-group">
                <select name="image_section_{{ $key + 1 }}" class="form-control">
                    <option value=''> Select section </option>
                    @foreach (config('params.car.image-section') as $value => $label)
                    <option value="{{ $value }}">
                        {{ $label }}</option>
                    @endforeach
                </select>
                @error('image_section_{{ $key + 1 }}')
                    <span class="error" role="alert">{{ $message }}</span>
                @enderror
            </div>
           
        </div>
        <div class="col-md-3">
            <input id="image_{{ $key + 1 }}" type="file" name="image_{{ $key + 1 }}" class="form-control"
                onchange="previewImage(event, {{ $key + 1 }})" />
            <input type="hidden" id="image_old_{{ $key + 1 }}" name="image_old_{{ $key + 1 }}"
                value="">
         
            <span class="error" role="alert">
                @error('image_' . ($key + 1))
                    {{ $message }}</br>
                @enderror
            </span>
        </div>
  
        <div class="col-mod-3">
                <img src="" alt="" id="image_preview_{{ $key + 1 }}" class="img-thumbnail"
                    width="100" height="150" style="display:none;">
        </div>
        <div class="col-md-2">
                <button id="remove-image_{{ $key + 1 }}" type="button" class="btn btn-danger"
                    style="display:none">
                    x
                </button>
        </div>
     
    </div>
@endfor


               

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
            img.src = event.target.result;
            img.width = 100;
            img.height = 150;
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
