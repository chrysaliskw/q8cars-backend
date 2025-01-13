<x-admin-layout title="Offers">
    <link href="{{ url('moltran-asset/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.offers.index') }}">Offers</a></li>
        <li class="active">Create</li>
    </x-slot>
    <x-crud-create title="Offers">
        <x-form method="POST" action="{{ route('admin.offers.store') }}" class="form" enctype="multipart/form-data">
            @csrf
            @method('POST')
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
            <br>

            <div class="row">
                <div class="col-md-6">
                            <div class="col-sm-12">
                                <div class="card">
                                    <label for="key_feature_1">Key Feature 1</label>
                                    <div class="card-body">
                                        <textarea class="summernote form-control" rows="9" name="key_feature_1">{{ old('key_feature_1') }}</textarea>
                                    </div>
                                    <span class="error" role="alert">
                                        @error('key_feature_1')
                                            {{ $message }}</br>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                </div>

                    <div class="col-md-6">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <label for="key_feature_2">Key Feature 2</label>
                                        <div class="card-body">
                                            <textarea class="summernote form-control" rows="9" name="key_feature_2">{{ old('key_feature_2') }}</textarea>
                                        </div>
                                        <span class="error" role="alert">
                                            @error('key_feature_2')
                                                {{ $message }}</br>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                    </div>
            </div>
            <br>

            <div class="row">

                <div class="col-md-4">
                    <x-form-input type="file" field="key_icon_1" field-name="Key Icon 1" value="{{ old('key_icon_1') }}">
                    </x-form-input>
                    <span class="text-muted">
                            {{'Max size : 2MB'}}
                            <br>
                            {{'Dimensions : 34x35'}}
                            <br>
                            {{'Format : PNG'}}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <x-form-input type="file" field="key_icon_2" field-name="Key Icon 2" value="{{ old('key_icon_2') }}">
                        </x-form-input>
                        <span class="text-muted">
                            {{'Max size : 2MB'}}
                            <br>
                            {{'Dimensions : 34x35'}}
                            <br>
                            {{'Format : PNG'}}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <x-form-input type="text" field="offer" field-name="Offer" value="{{ old('offer') }}"></x-form-input>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-md-12">
                        <x-form-textarea field="title" field-name="Title" field-value="{{ old('title') }}"></x-form-textarea>
                    </div>
                </div>
                <br>

                {{--   <div class="row">
                <div class="col-md-12">
                        <x-form-textarea class="summernote form-control" field="description" field-name="Description" field-value="{{ old('description') }}"></x-form-textarea>
                    </div>
                   <div class="col-md-12">
                        <div class="card">
                            <label for="description">Description</label>
                            <div class="card-body">
                                <textarea class="summernote form-control" rows="9" name="description">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>--}}
                <div class="row">
                <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <label for="description">Description</label>
                                    <div class="card-body">
                                        <textarea class="summernote form-control" rows="9" name="description">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        @error('description')
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                 
                </div>
            </div>
                <br>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="start_date" class="control-label">Start Date</label>
                            <div class="input-group">
                                <input type="text" name="start_date" id="start_date" class="form-control"
                                    value="{{ old('start_date') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="md md-event"></i></span>
                                </div>
                            </div>
                            @error('start_date')
                                <span class="error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end_date" class="control-label">End Date</label>
                            <div class="input-group">
                                <input type="text" name="end_date" id="end_date" class="form-control"
                                    value="{{ old('end_date') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="md md-event"></i></span>
                                </div>
                            </div>
                            @error('end_date')
                                <span class="error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                            @foreach (config('params.offers.status') as $value => $label)
                                <option {{ old('status') == $value ? 'Selected' : '' }} value="{{ $value }}">
                                    {{ $label }}</option>
                            @endforeach
                        </x-form-select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label for="show_in_suggestions">Show in Suggestions</label>
                        <input type="checkbox" name="show_in_suggestions" id="show_in_suggestions" value="1" {{ old('show_in_suggestions') ? 'checked' : '' }}>
                    </div>
                </div>
                <br>
            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-create>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <x-slot name="scripts">

    <script src="{{ url('moltran-asset/plugins/summernote/summernote-bs4.js') }}"></script>

    <script>

        jQuery(document).ready(function(){


            $('.summernote').summernote({
                    height: 200, // set editor height

                    minHeight: null, // set minimum height of editor
                    maxHeight: null, // set maximum height of editor

                    focus: true, // set focus to editable area after initializing summernote
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
                        ['insert', ['picture']],
                    ],
                });

            $('#start_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            }).on('changeDate', function(e) {
                $('#end_date').datepicker('setStartDate', e.date);
                $('#end_date').val('');
            });

            $('#end_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
            $('#end_date').datepicker('setStartDate', new Date());



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
