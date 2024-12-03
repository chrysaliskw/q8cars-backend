<x-admin-layout title="News">
    <link href="{{ url('moltran-asset/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.news.index') }}">News</a></li>
        <li class="active">Update</li>
    </x-slot>

    <x-crud-update title="News">
        <x-form method="PUT" action="{{ route('admin.news.update', $news) }}" class="form"
            enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4">
                    <x-form-select field="brand_id" field-name="Brand" id="brand_id">
                    </x-form-select>
                    <input type="hidden" id="brand_id_text" name="brand_id_text" />
                </div>
                <div class="col-md-4">
                    <x-form-select field="car_id" field-name="Car Model" id="car_id">
                    </x-form-select>
                    <input type="hidden" id="car_id_text" name="car_id_text" />
                </div>
                <div class="col-md-4">
                    <x-form-select field="car_version_id" field-name="Car Version" id="car_version_id">
                    </x-form-select>
                    <input type="hidden" id="car_version_id_text" name="car_version_id_text" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="image" class="control-label">Image/Thumbnail</label><br>
                        <input id="image" type="file" name="image" class="form-control">
                        <br>
                        <img src="{{ file_asset('files-news', $news->image) }}" alt="news-img"
                            class="img-thumbnail" width="100" height="150">
                        <span class="error" role="alert">
                            @error('image')
                                {{ $message }}</br>
                            @enderror
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="media_name" field-name="Media Name"
                        value="{{ $news->media_name }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">

                    <div class="form-group">
                        <label for="media_logo" class="control-label">Media Logo</label><br>
                        <input id="media_logo" type="file" name="media_logo" class="form-control">
                        <br>
                        <img src="{{ file_asset('files-news', $news->media_logo) }}" alt="news-img"
                            class="img-thumbnail" width="100" height="150">
                        <span class="error" role="alert">
                            @error('media_logo')
                                {{ $message }}</br>
                            @enderror
                        </span>
                    </div>
                </div>



             </div>


            <div class="row">
                <div class="col-md-12">
                    <x-form-input type="text" field="title" field-name="Title" value="{{ $news->title }}">
                    </x-form-input>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <label for="content">Content</label>
                                    <div class="card-body">
                                        <textarea class="summernote form-control" rows="9" name="content">{{ $news->html_content}}</textarea>
                                    </div>
                                    <span class="error" role="alert">

                                        @error('content')
                                            {{ $message }}</br>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>

                </div>
            </div>


            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="expiry_date" class="control-label">Expiry Date*</label>
                        <div class="input-group">
                            <input type="text" name="expiry_date" id="expiry_date" class="form-control"
                                value="{{ date('d-m-Y', strtotime($news->expiry_date)) }}">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="md md-event"></i></span>
                            </div>
                        </div>
                        @error('expiry_date')
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                {{--    <div class="col-md-4">
                    <x-form-input type="text" field="sort_order" field-name="Sort Order" value="{{ $news->sort_order }}"></x-form-input>
                </div> --}}
                <div class="col-md-4">
                    <x-form-select field="is_trending" field-name="Is Trending ?" >
                        @foreach (config('params.news.is_trending') as $value => $label)
                            <option {{ $news->is_trending == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="read_time" field-name="Read time(min)" value="{{ $news->read_time }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach (config('params.news.status') as $value => $label)
                            <option {{ $news->status == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
               {{-- <div class="col-md-4" style="display: flex;align-items: center;padding-left: 2em;">
                    <input class="form-check-input" type="checkbox" name="notification" value="1">Allow Notification?
                </div>--}}
            </div>
            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-update>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <x-slot name="scripts">
    <script src="{{ url('moltran-asset/plugins/summernote/summernote-bs4.js') }}"></script>

    <script>

        jQuery(document).ready(function(){

            $('.summernote').summernote({
                height: 200,                 // set editor height

                minHeight: null,             // set minimum height of editor
                maxHeight: null,             // set maximum height of editor

                focus: true,                 // set focus to editable area after initializing summernote
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline']],
                    // ['style', ['bold', 'italic', 'underline', 'clear']],
                    // ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    // ['color', ['color']],
                    ['para', ['ul', 'ol']],
                    // ['height', ['height']]
                    ['insert', [ 'picture']],
                ]
            });

        });
    </script>

        <script type="application/javascript">

            $("#expiry_date").datepicker({
                format: 'dd-mm-yyyy',
                startDate: 'today',

            });

            $('#brand_id').select2({

                placeholder: "Search brand",
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('admin.brand.select') }}",
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
                }
            });
            $('#brand_id').on('select2:select', function (e) {
                const data = e.params.data;
                $("#brand_id_text").val(data.text);
                // Reset car_id when brand_id changes
                $('#car_id').val(null).trigger('change');
                $('#car_id_text').val(null).trigger('change');
                $('#car_version_id').val(null).trigger('change'); // Optionally reset car_version_id as well
                $('#car_version_id_text').val(null).trigger('change');
            });

            if('{!! $currentBrand !!}') {
                const currentBrand = JSON.parse('{!! $currentBrand !!}');
                const brandOption = new Option(currentBrand.text, currentBrand.id, true, true);
                $('#brand_id').append(brandOption).trigger('change');
            }


            $('#car_id').select2({

                placeholder: "Search car",
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('admin.car.select') }}",
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                            brand_id: $('#brand_id').val(),
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
                }
            });

            $('#car_id').on('select2:select', function (e) {
                const data = e.params.data;
                $("#car_id_text").val(data.text);
                // Reset car_version_id when brand_id changes
                $('#car_version_id').val(null).trigger('change');
                $('#car_version_id_text').val(null).trigger('change');

            });

            if('{!! $currentCar !!}') {
                const currentCar = JSON.parse('{!! $currentCar !!}');
                const cOption = new Option(currentCar.text, currentCar.id, true, true);
                $('#car_id').append(cOption).trigger('change');
            }

            $('#car_version_id').select2({

                placeholder: "Search Car version",
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('admin.car-version.select') }}",
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                            car_id: $('#car_id').val(),
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
                }
            });
            $('#car_version_id').on('select2:select', function (e) {
                const data = e.params.data;
                $("#car_version_id_text").val(data.text);


            });

            if('{!! $currentVersion !!}') {
                const currentVersion = JSON.parse('{!! $currentVersion !!}');
                const vOption = new Option(currentVersion.text, currentVersion.id, true, true);
                $('#car_version_id').append(vOption).trigger('change');
            }

        </script>
    </x-slot>
</x-admin-layout>
