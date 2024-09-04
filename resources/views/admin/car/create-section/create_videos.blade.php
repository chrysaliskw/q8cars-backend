<div class="row">
    <div class="col-md-4">
        <x-form-input type="text" field="title_1" field-name="Title" value="{{ old('title_1') }}">
        </x-form-input>
    </div>
    <div class="col-md-4">
        <x-form-textarea type="text" field="description_1" field-name="Description" field-value="{{ old('description_1') }}">
        </x-form-textarea>
    </div>
  
    <div class="col-md-4">
        <x-form-input type="text" field="posted_media_1" field-name="Posted Media" value="{{ old('posted_media_1') }}">
        </x-form-input>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="date_1" class="control-label">Posted Date</label>
            <div class="input-group">
                <input type="text" name="date_1" id="date_1" class="form-control"
                    value="{{ old('date_1') }}">
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
        <x-form-input type="file" field="thumbnail_1" field-name="Thumbnail" value="{{ old('thumbnail_1') }}">
        </x-form-input>
        <span class="text-muted">
            {{'Max size : 2MB'}} 
        </span>
    </div>
    <div class="col-md-4">
        <x-form-input type="file" field="video_1" field-name="Video File" value="{{ old('video_1') }}">
        </x-form-input>
        <span class="text-muted">
            {{' MP4 only ,Max size : 2MB'}} 
        </span>
    </div>
</div>
<hr><br>
<div class="row">
    <div class="col-md-4">
        <x-form-input type="text" field="title_2" field-name="Title" value="{{ old('title_2') }}">
        </x-form-input>
    </div>
    <div class="col-md-4">
        <x-form-textarea type="text" field="description_2" field-name="Description" field-value="{{ old('description_2') }}">
        </x-form-textarea>
    </div>
    <div class="col-md-4">
        <x-form-input type="text" field="posted_media_2" field-name="Posted Media" value="{{ old('posted_media_2') }}">
        </x-form-input>
    </div>
  
    <div class="col-md-4">
        <div class="form-group">
            <label for="date_2" class="control-label">Posted Date</label>
            <div class="input-group">
                <input type="text" name="date_2" id="date_2" class="form-control"
                    value="{{ old('date_2') }}">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="md md-event"></i></span>
                </div>
            </div>
            @error('date_2')
                <span class="error" role="alert">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <x-form-input type="file" field="thumbnail_2" field-name="Thumbnail" value="{{ old('thumbnail_2') }}">
        </x-form-input>
        <span class="text-muted">
            {{'Max size : 2MB'}} 
        </span>
    </div>
    <div class="col-md-4">
        <x-form-input type="file" field="video_2" field-name="Video File" value="{{ old('video_2') }}">
        </x-form-input>
        <span class="text-muted">
            {{' MP4 only ,Max size : 2MB'}} 
        </span>
    </div>
</div>
<hr><br>
<div class="row">
    <div class="col-md-4">
        <x-form-input type="text" field="title_3" field-name="Title" value="{{ old('title_3') }}">
        </x-form-input>
    </div>
    <div class="col-md-4">
        <x-form-textarea type="text" field="description_3" field-name="Description" field-value="{{ old('description_3') }}">
        </x-form-textarea>
    </div>
    <div class="col-md-4">
        <x-form-input type="text" field="posted_media_3" field-name="Posted Media" value="{{ old('posted_media_3') }}">
        </x-form-input>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="date_3" class="control-label">Posted Date</label>
            <div class="input-group">
                <input type="text" name="date_3" id="date_3" class="form-control"
                    value="{{ old('date_3') }}">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="md md-event"></i></span>
                </div>
            </div>
            @error('date_3')
                <span class="error" role="alert">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <x-form-input type="file" field="thumbnail_3" field-name="thumbnail" value="{{ old('thumbnail_3') }}">
        </x-form-input>
        <span class="text-muted">
            {{'Max size : 2MB'}} 
        </span>
    </div>
    <div class="col-md-4">
        <x-form-input type="file" field="video_3" field-name="Video File" value="{{ old('video_3') }}">
        </x-form-input>
        <span class="text-muted">
            {{' MP4 only ,Max size : 2MB'}} 
        </span>
    </div>
</div>