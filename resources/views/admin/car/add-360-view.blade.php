<x-admin-layout title="Car 360 View Image">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.car.index') }}">Cars</a></li>
        <li class="active">Create</li>
    </x-slot>

    <x-crud-create>
        <x-form method="POST" action="{{ route('admin.car.360-view.store') }}" class="form" enctype="multipart/form-data">
            @csrf
            <!-- First Row: Image field -->
            <div class="row align-items-center" style="margin-bottom: 20px;">
                <div class="col-md-4">
                    <input type="file" id="image-upload" name="picture" class="form-control" accept="image/*">
                </div>
              
                <div class="col-md-4" style="padding-left: 10px;">
                    <button type="button" class="btn btn-primary" id="upload-button" >Upload Image</button>
                </div>
             
            </div>

            <!-- Uploaded Images Preview -->
            <div id="uploaded-images" style="margin-top: 20px;">
                <h5>Uploaded Images ({{ count($images) }}/10):</h5>
                <div class="row" id="image-preview-container">
                    @foreach($images as $image)
                        <div class="col-md-3" style="margin-bottom: 10px;">
                            <div class="image-preview text-center" style="position: relative; padding: 1px; border: 1px solid #ccc; border-radius: 1px;">
                                <img src="{{ file_asset('files-360_view', $image->image) }}" alt="Uploaded Image" 
                                    style="width: 180px; height: 120px; object-fit: cover; margin-bottom: 10px;">

                                <!-- Buttons Row -->
                                <div class="btn-group d-flex justify-content-between">
                                  <!-- Pencil (Edit) Button -->
                                    <button style="margin:10px" type="button" class="btn btn-info btn-sm" 
                                            onclick="triggerFileInput(this,{{ $image->id }})">
                                        <i class="fa fa-pencil"></i>
                                    </button>                                    
                                    <!-- Remove Button -->
                                    <button style="margin:10px" type="button" class="btn btn-danger btn-sm" 
                                            onclick="removeImage(this, {{ $image->id }},2)">
                                            <i class="fa fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Hidden File Input -->
                                <input type="file" class="file-input" 
                                    style="display: none;" 
                                    accept="image/*" 
                                    onchange="handleFileSelection(this, {{ $image->id }})">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <input type="hidden" id="id" name="id" value="{{ $car->id }}">
            <p id="max-limit-message" style="color: red; display: none;">You can only upload up to 10 images.</p>

            <div class="row align-items-center" style="margin-bottom: 20px; margin-top:20px">
                <div class="col-md-4">
                    <input type="text" id="url-upload" name="url" class="form-control" placeholder="Enter a valid Url" value="{{$url ?$url->image : old('url') }}">
                </div>
              
                <div class="col-md-4" style="padding-left: 10px;">
                    <button type="button" class="btn btn-primary" id="upload-url_button" >Upload Url</button>
                </div>
            </div>
            <div class="row align-items-center" style="margin-bottom: 20px; margin-top:20px" id="urlcontainer">
                @if($url)
                <div id="uploaded-url" style="margin-top: 10px;">
                    <h5>Uploaded URL:</h5>
                    <iframe src="{{ $url->image }}" style="width: 100%; height: 300px; border: 1px solid #ccc;"></iframe></div>
                    <div class="mt-2" style="margin-left: 10px;">
                        <button type="button" class="btn btn-danger"  onclick="removeImage(this, {{ $url->id }},1)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                
            @endif
            </div>
        </x-form>
    </x-crud-create>

    <script>
        const maxImages = 10;
        let uploadedImagesCount = {{ count($images) }};

        // Enable Upload Button if file is selected
        document.getElementById('image-upload').addEventListener('change', function () {
            const uploadButton = document.getElementById('upload-button');
            if (this.files.length > 0 && uploadedImagesCount < maxImages) {
                uploadButton.disabled = false;
            } else {
                uploadButton.disabled = true;
            }
        });
        
        // Upload Image
        document.getElementById('upload-button').addEventListener('click', function () {
            const fileInput = document.getElementById('image-upload');
            const file = fileInput.files[0];
            const carId = document.getElementById('id').value;

            if (!file || uploadedImagesCount >= maxImages) return;

            const formData = new FormData();
            formData.append('picture', file);
            formData.append('id', carId);

            fetch('{{ route('admin.car.360-view.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            })
            .then(response => response.json())
            .then(data => {
            if (data.success) {
                uploadedImagesCount++;
                if(uploadedImagesCount == 10){
                    document.getElementById('upload-button').style.display = 'none';
                }
                const previewContainer = document.getElementById('image-preview-container');
                
                // Create a new div for the uploaded image
                const newImage = document.createElement('div');
                newImage.classList.add('col-md-3'); // Ensure the same grid class is used
                newImage.style.marginBottom = '10px'; // Consistent margin
                newImage.innerHTML = `
                    <div class="image-preview text-center" style="position: relative; padding: 1px; border: 1px solid #ccc; border-radius: 1px;">
                        <!-- Display Image -->
                        <img src="${data.image_url}" alt="Uploaded Image" 
                            style="width: 180px; height: 120px; object-fit: cover; margin-bottom: 10px;">

                        <!-- Buttons Row -->
                        <div class="btn-group d-flex justify-content-between">
                            <!-- Pencil (Edit) Button -->
                            <button style="margin:10px" type="button" class="btn btn-info btn-sm" 
                                    onclick="triggerFileInput(this, ${data.image_id})">
                                <i class="fa fa-pencil"></i>
                            </button>

                            <!-- Remove Button -->
                            <button style="margin:10px" type="button" class="btn btn-danger btn-sm" 
                                    onclick="removeImage(this, ${data.image_id},2)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>

                        <!-- Hidden File Input -->
                        <input type="file" class="file-input" 
                            style="display: none;" 
                            accept="image/*" 
                            onchange="handleFileSelection(this, ${data.image_id})">
                    </div>
                `;

                // Append the new image preview to the container
                previewContainer.appendChild(newImage);

                // Update the counter in the header
                document.querySelector('#uploaded-images h5').textContent = `Uploaded Images (${uploadedImagesCount}/${maxImages}):`;

                // Reset the file input and disable the upload button
                fileInput.value = '';
                document.getElementById('upload-button').disabled = true;
                alert("Image uploaded successfully!");
            
            } else {
                alert(data.message || 'Upload failed.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred during the upload.');
        });

     });

        // Remove Image
        function removeImage(button, imageId,type) {
            if (!confirm('Are you sure you want to delete this image?')) return;
console.log(imageId);
            fetch('{{ route('admin.car.360-view.delete') }}', {
                method: 'POST',
                body: JSON.stringify({ image_id: imageId }),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if(type == 2)
                    {
                        const imageContainer = button.closest('.col-md-3');
                        imageContainer.remove();
                        uploadedImagesCount--;
                        document.querySelector('#uploaded-images h5').textContent = `Uploaded Images (${uploadedImagesCount}/${maxImages}):`;
                        if (uploadedImagesCount < maxImages) {
                            document.getElementById('max-limit-message').style.display = 'none';
                            console.log('sdnfjsdn');
                        }
                        alert("Image deleted successfully!");
                        if(uploadedImagesCount < 10){
                        document.getElementById('upload-button').style.display = 'block';
                        }
                    }else{
                        alert("URL deleted successfully!");
                        location.reload(); // Reload the page to reflect changes for URL removal
                    }
                } else {
                    alert(data.message || 'Delete failed.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during the delete.');
            });
        }
        function triggerFileInput(button, imageId) {
    const fileInput = button.closest('.image-preview').querySelector('.file-input'); // Find the corresponding file input
    fileInput.setAttribute('data-image-id', imageId); // Store the image ID in the file input
    fileInput.click(); // Trigger the file input
}

function handleFileSelection(input,imageId) {
    const file = input.files[0]; // Get the selected file
    // const imageId = input.getAttribute('data-image-id'); // Retrieve the image ID from the file input

    if (!file || !imageId) return;

    // Create a FormData object
    const formData = new FormData();
    formData.append('picture', file);
    formData.append('image_id', imageId);

    // Make a POST request to the update route
    fetch('{{ route('admin.car.360-view.update') }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}', // Add CSRF token for security
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the image preview with the new uploaded image
            const imagePreview = input.closest('.image-preview').querySelector('img');
            imagePreview.src = data.image_url; // Update the image URL with the new one
            alert("Image updated successfully!");
        } else {
            alert(data.message || "Failed to update image.");
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while updating the image.");
    });
}

    document.getElementById('upload-url_button').addEventListener('click', function () {
        const urlInput = document.getElementById('url-upload'); // Get the URL input field
        const urlValue = urlInput.value.trim(); // Get the trimmed URL value
        const carId = document.getElementById('id').value; 

        // Validate the URL
        if (!urlValue) {
            alert('URL field cannot be empty.');
            return;
        }

        try {
            new URL(urlValue); // Check if the URL is valid

        } catch (error) {
            alert('Please enter a valid URL.');
            return;
        }

       // Prepare the form data
        const formData = new FormData();
        formData.append('url', urlValue);
        formData.append('id', carId);

    // Send the request to the server
    fetch('{{ route('admin.car.360-view.store_url') }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log(data);
            alert('URL uploaded successfully!');
           const uploadedUrlContainer =  document.getElementById('urlcontainer');
            if (uploadedUrlContainer) {
                uploadedUrlContainer.innerHTML = `
                 <div id="uploaded-url" style="margin-top: 10px;">
                    <h5>Uploaded URL:</h5>
                    <iframe src="${data.image_url}" style="width: 100%; height: 300px; border: 1px solid #ccc;"></iframe></div>
                    <div class="mt-2" style="margin-left: 10px;">
                        <button type="button" class="btn btn-danger" onclick="removeImage(this,${data.id},1)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                `;

            }

        } else {
            alert(data.message || 'Failed to upload URL.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred during the upload.');
    });
});

</script>
</x-admin-layout>