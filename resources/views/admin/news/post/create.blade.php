<x-admin-layout title="News">
       
    <link href="{{ url('moltran-asset/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.news.index') }}">News</a></li>
        <li class="active">Create</li>
    </x-slot>
    
    <x-crud-create title="News">
        <x-form method="POST" action="{{ route('admin.news.store') }}" class="form" enctype="multipart/form-data">
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
                        <x-form-input type="file" field="image" field-name="Image/Thumbnail"
                        value="{{ old('image') }}"></x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="media_name" field-name="Media Name" 
                        value="{{ old('author_name') }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="file" field="media_logo" field-name="Media Logo (maximum limit : 2MB)"
                        value="{{ old('media_logo') }}">
                    </x-form-input>         
                </div>
                
             </div>
            <div class="row">

                <div class="col-md-12">
                    <x-form-input type="text" field="title" field-name="Title" value="{{ old('title') }}">
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
                                        <textarea class="summernote form-control" rows="9" name="content">{{ old('content') }}</textarea>
                                    </div>
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
                                value="{{ old('expiry_date') }}">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="md md-event"></i></span>
                            </div>
                        </div>
                        @error('expiry_date')
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <x-form-select field="is_trending" field-name="Is Trending ?" >
                        @foreach (config('params.news.is_trending') as $value => $label)
                            <option {{ old('is_trending') == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="read_time" field-name="Read time(min)" value="{{ old('read_time') }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    @php 
                        $status = config('params.news.status');
                        unset($status[3]);
                    @endphp
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach ($status as $value => $label)
                            <option {{ old('status') == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
              {{--  <div class="col-md-4"
                    style="display: flex;
                align-items: center;
                padding-left: 2em;">
                    <input class="form-check-input" type="checkbox" name="notification" value="1">Allow Notification?
                </div>--}}
            </div>
            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-create>

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
                        // ['para', ['ul', 'ol', 'paragraph']],
                        ['para', ['ul', 'ol']],
                        // ['height', ['height']]
                        ['insert', [ 'picture']],
                    ],
                });

            });
        </script>

        <script type="application/javascript">

            $("#expiry_date").datepicker({
                format: 'dd-mm-yyyy',
                startDate: 'today',
         
            });

            
          
       
        </script>

        <script type="application/javascript">

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

            if('{!! old("brand_id") !!}' && '{!! old("brand_id_text") !!}') {
                const countryOption = new Option('{{ old("brand_id_text") }}', '{{ old("brand_id") }}', true, true);
                $('#brand_id').append(countryOption).trigger('change');
                $("#brand_id_text").val('{{ old("brand_id_text") }}');
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

            if('{!! old("car_id") !!}' && '{!! old("car_id_text") !!}') {
                const cOption = new Option('{{ old("car_id_text") }}', '{{ old("car_id") }}', true, true);
                $('#car_id').append(cOption).trigger('change');
                $("#car_id_text").val('{{ old("car_id_text") }}');
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

            if('{!! old("car_version_id") !!}' && '{!! old("car_version_id_text") !!}') {
                const vOption = new Option('{{ old("car_version_id_text") }}', '{{ old("car_version_id") }}', true, true);
                $('#car_version_id').append(vOption).trigger('change');
                $("#car_version_id_text").val('{{ old("car_version_id_text") }}');
            }

        </script>
    </x-slot>
</x-admin-layout>
