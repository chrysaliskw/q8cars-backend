<!-- Container for all sections -->
<!-- Add Button -->
<div class="text-center mt-3">
    <button type="button" class="btn btn-primary" onclick="addFormSection()" style="margin-left: 800px;">+ Add
        Video</button>
</div>
<div id="form-sections-container">
    @php
        $carVideoCount = $carVideos->count();
    @endphp
    @foreach ($carVideos as $key => $carVideo)
        <div class="row form-section" id="form-section-{{ $key + 1 }}">
            <div class="col-md-4">
                <x-form-input type="text" field="title_{{ $key + 1 }}" field-name="Title"
                    value="{{ $carVideo->video_title }}"></x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-textarea type="text" field="description_{{ $key + 1 }}" field-name="Description"
                    field-value="{{ $carVideo->video_description }}"></x-form-textarea>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="posted_media_{{ $key + 1 }}" field-name="Posted Media"
                    value="{{ $carVideo->video_posted_media }}"></x-form-input>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="date_{{ $key + 1 }}" class="control-label">Posted Date</label>
                    <div class="input-group">
                        <input type="text" name="date_{{ $key + 1 }}" id="date_{{ $key + 1 }}"
                            class="form-control" value="{{ $carVideo->video_posted_date }}">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="md md-event"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="thumbnail_{{ $key + 1 }}" class="control-label">Thumbnail</label><br>
                    @if ($carVideo->thumbnail)
                        <img src="{{ file_asset('files-car', $carVideo->thumbnail) }}" alt="brand-img"
                            class="img-thumbnail" width="100" height="150">
                    @endif
                    <input id="thumbnail_{{ $key + 1 }}" type="file" name="thumbnail_{{ $key + 1 }}"
                        class="form-control">
                        <span class="text-muted">Max size: 2MB,resolution:415 × 280 px</span>
                    @error('thumbnail_' . ($key + 1))
                        <span class="error" role="alert">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            {{-- <div class="col-md-4">
                <x-form-input type="file" field="video_{{ $key + 1 }}" field-name="Video File"
                    value=""></x-form-input>
                <span class="text-muted">MP4 only, Max size: 2MB</span>
            </div> --}}
            <div class="col-md-4">
                <div class="form-group">



                    <label for="video_{{ $key + 1 }}" class="control-label">Video File</label><br>
                    @if($carVideo->file_name)
                    <video  width="100" height="150" controls class="img-thumbnail">
                        <source src="{{ file_asset('files-car', $carVideo->file_name) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    @endif
                    <input id="video_{{ $key + 1 }}" type="file" name="video_{{ $key + 1 }}" class="form-control"
                        onchange="previewVideo(event, $key+1)">
                    <span class="error" role="alert">
                        @error('video_' . ($key + 1))
                            {{ $message }}</br>
                        @enderror
                    </span>
                    
                </div>
            </div>
        </div>
        <hr><br>
    @endforeach
</div>



<!-- Script -->
<script>
    let sectionCount = {{ $carVideoCount }}; // Initialize section count

    function addFormSection() {
        // Allow only 3 sections
        if (sectionCount >= 3) {
            alert("You can't add more than 3 sections.");
            return;
        }

        sectionCount++;
        const container = document.getElementById('form-sections-container');

        // Use backticks but escape Blade curly brackets
        const newSection = `

            <div class="row form-section" id="form-section-${sectionCount}">
                <div class="col-md-4">
                    <x-form-input type="text" field="title_${sectionCount}" field-name="Title" value=""></x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-textarea type="text" field="description_${sectionCount}" field-name="Description" field-value=""></x-form-textarea>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="posted_media_${sectionCount}" field-name="Posted Media" value=""></x-form-input>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date_${sectionCount}" class="control-label">Posted Date</label>
                        <div class="input-group">
                            <input type="text" name="date_${sectionCount}" id="date_${sectionCount}" class="form-control"
                                value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="md md-event"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <x-form-input type="file" field="thumbnail_${sectionCount}" field-name="Thumbnail" value=""></x-form-input>
                    <span class="text-muted">Max size: 2MB,resolution:415 × 280 px</span>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="video_${sectionCount}" class="control-label">Video File</label><br>
                        <input id="video_${sectionCount}" type="file" name="video_${sectionCount}" class="form-control"
                            onchange="previewVideo(event, ${sectionCount})">
                       
                    </div>
                </div>
            </div>
        </div>
             <hr><br>
        `;

        // Append the new section
        container.insertAdjacentHTML('beforeend', newSection);
    }
</script>
