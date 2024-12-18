@foreach ($carVideos as $key => $carVideo)
<div class="row">

    <div class="col-md-4">
        <x-form-input type="text" field="title_{{$key+1}}" field-name="Title" value="{{ $carVideo->video_title }}">
        </x-form-input>
    </div>
    <div class="col-md-4">
        <x-form-textarea type="text" field="description_{{$key+1}}" field-name="Description" field-value="{{ $carVideo->video_description }}">
        </x-form-textarea>
    </div>

    <div class="col-md-4">
        <x-form-input type="text" field="posted_media_{{$key+1}}" field-name="Posted Media" value="{{ $carVideo->video_posted_media }}">
        </x-form-input>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="date_{{$key+1}}" class="control-label">Posted Date</label>
            <div class="input-group">
                <input type="text" name="date_{{$key+1}}" id="date_{{$key+1}}" class="form-control"
                    value="{{ $carVideo->video_posted_date }}">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="md md-event"></i></span>
                </div>
            </div>
            @error('date_ . ($key+1)')
                <span class="error" role="alert">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
                    <div class="form-group">
                        <label for="thumbnail_{{$key+1}}" class="control-label">Thumbnail</label><br>
                        @if($carVideo->thumbnail)
                        <img src="{{ file_asset('files-car', $carVideo->thumbnail) }}"
                            alt="brand-img" class="img-thumbnail" width="100" height="150">
                        @endif
                        <input id="thumbnail_{{$key+1}}" type="file" name="thumbnail_{{$key+1}}" class="form-control">
                        <span class="error" role="alert">
                            @error('thumbnail_'.($key+1))
                                {{ $message }}</br>
                            @enderror
                        </span>
                        <span class="text-muted">
                            {{'Max size : 2MB'}}
                        </span>
                    </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
        <label for="video_{{$key+1}}" class="control-label">Video File</label><br>
        @if($carVideo->file_name)

            <video  width="100" height="150" controls autoplay class="img-thumbnail">
                <source src="{{ file_asset('files-car', $carVideo->file_name) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @endif
        {{-- <x-form-input
            type="file"
            field="video_{{$key+1}}"
            field-name=""
            >

        </x-form-input> --}}
        <input id="video_{{$key+1}}" type="file" name="video_{{$key+1}}" class="form-control ">
        <span class="error" role="alert">
            @error('video_'.($key+1))
                {{ $message }}</br>
            @enderror
        </span>
        </div>

    </div>

</div>
<hr><br>
@endforeach

