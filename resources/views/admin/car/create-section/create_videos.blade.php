<!-- Add Button -->
<div class="text-center mt-3" >
    <button type="button" class="btn btn-primary" onclick="addFormSection()" style="margin-left: 800px;">+ Add Video</button>
</div>
<div id="form-sections-container">
    <div class="row form-section" id="form-section-1">
        <div class="col-md-4">
            <x-form-input type="text" field="title_1" field-name="Title" value="{{ old('title_1') }}"></x-form-input>
        </div>
        <div class="col-md-4">
            <x-form-textarea type="text" field="description_1" field-name="Description" field-value="{{ old('description_1') }}"></x-form-textarea>
        </div>
        <div class="col-md-4">
            <x-form-input type="text" field="posted_media_1" field-name="Posted Media" value="{{ old('posted_media_1') }}"></x-form-input>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="date_1" class="control-label">Posted Date</label>
                <div class="input-group">
                    <input type="text" name="date_1" id="date_1" class="form-control" 
                        value="{{ old('date_1', \Carbon\Carbon::today()->format('Y-m-d')) }}">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="md md-event"></i></span>
                    </div>
                </div>
                @error('date_1')
                    <span class="error" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <x-form-input type="file" field="thumbnail_1" field-name="Thumbnail" value="{{ old('thumbnail_1') }}"></x-form-input>
            <span class="text-muted">Max size: 2MB,resolution:415 × 280 px</span>
        </div>
        <div class="col-md-4">
            <x-form-input type="file" field="video_1" field-name="Video File" value="{{ old('video_1') }}"></x-form-input>
            <span class="text-muted">MP4 only, Max size: 2MB</span>
        </div>
    </div>
</div>



<script>
    let sectionCount = 1; // Start with section 1

    function addFormSection() {
        // Allow only 3 sections
        if (sectionCount >= 3) {
            alert("You can't add more than 3 sections.");
            return;
        }

        sectionCount++;
        const container = document.getElementById('form-sections-container');

        const newSection = `
            <hr>
            <div class="row form-section" id="form-section-${sectionCount}">
                <div class="col-md-4">
                    <x-form-input type="text" field="title_${sectionCount}" field-name="Title" value="{{ old('title_${sectionCount}') }}"></x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-textarea type="text" field="description_${sectionCount}" field-name="Description" field-value="{{ old('description_${sectionCount}') }}"></x-form-textarea>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="posted_media_${sectionCount}" field-name="Posted Media" value="{{ old('posted_media_${sectionCount}') }}"></x-form-input>
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
                    <x-form-input type="file" field="video_${sectionCount}" field-name="Video File" value=""></x-form-input>
                    <span class="text-muted">MP4 only, Max size: 2MB</span>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', newSection);
    }
</script>
