<x-admin-layout title="Offers">
    <link href="{{ url('moltran-asset/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.offers.index') }}">Offers</a></li>
        <li class="active">Update</li>
    </x-slot>
    <x-crud-create title="Offers">
        <div class="row">
        <x-form method="POST" action="{{ route('admin.offers.update', $offer) }}" class="form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-md-4">
                <x-form-textarea field="title" field-name="Title" field-value="{{ $offer->title }}"></x-form-textarea>
            </div>
            <div class="col-md-4">
                <label for="image">Image</label>
                <div class="col-md-9">
                    <input id="image" type="file" name="image" class="form-control">
                    <img src="{{ asset('storage/'. $offer->image) }}"
                    alt="offer-img" class="img-thumbnail" width="100" height="150">
                    <span class="error" role="alert">
                        @error('image')
                            {{ $message }}</br>
                        @enderror
                    </span>
                </div>
            </div>
            <div class="col-md-4" id="image-preview">
            </div>
            <div class="row">
                <div class="col-md-4">
                    <x-form-select field="brand_id" field-name="Brand" id="brand_id">
                        @if($offer->brand)
                            <option value="{{ $offer->brand_id }}" selected>{{ $offer->brand->name }}</option>
                        @endif
                    </x-form-select>
                    <input type="hidden" id="brand_id_text" name="brand_id_text"/>
                </div>
                <div class="col-md-4">
                    <x-form-select field="car_id" field-name="Car Model" id="car_id">
                        @if($offer->car)
                            <option value="{{ $offer->car_id }}" selected>{{ $offer->car->model_name }}</option>
                        @endif
                    </x-form-select>
                    <input type="hidden" id="car_id_text" name="car_id_text"/>
                </div>
                <div class="col-md-4">
                    <x-form-select field="car_version_id" field-name="Car Version" id="car_version_id">
                    </x-form-select>
                    <input type="hidden" id="car_version_id_text" name="car_version_id_text" />
                </div>
            </div>

                <div class="row">
                    <div class="col-md-4">
                        <x-form-textarea field="key_feature_1" field-name="Key Feature 1" field-value="{{ $offer->key_feature_1 }}"></x-form-textarea>
                    </div>
                    <div class="col-md-3">
                        <x-form-input type="file" field="key_icon_1" field-name="Key Icon 1">
                        </x-form-input>
                        <span class="text-muted">
                            {{'Max size : 2MB'}}
                        </span>
                        <img src="{{ asset('storage/'. $offer->key_icon_1) }}"
                        alt="key_icon_1" class="img-thumbnail" width="100" height="150">
                    </div>
                    <div class="col-md-4">
                        <x-form-textarea field="key_feature_2" field-name="Key Feature 2" field-value="{{ $offer->key_feature_2 }}"></x-form-textarea>
                    </div>
                    <div class="col-md-3">
                        <x-form-input type="file" field="key_icon_2" field-name="Key Icon 2">
                        </x-form-input>
                        <span class="text-muted">
                            {{'Max size : 2MB'}}
                        </span>
                        <img src="{{ asset('storage/'. $offer->key_icon_2) }}"
                        alt="key_icon_2" class="img-thumbnail" width="100" height="150">
                    </div>
                    <div class="col-md-4">
                        <x-form-textarea field="description" field-name="Description" field-value="{{ $offer->description }}"></x-form-textarea>
                    </div>
                    <div class="col-md-4">
                        <x-form-textarea field="html_description" field-name="HTML Description" field-value="{{ $offer->html_description }}"></x-form-textarea>
                    </div>
                    <div class="col-md-4">
                        <x-form-textarea field="offer" field-name="Offer" field-value="{{ $offer->offer }}"></x-form-textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <x-form-input type="date" field="start_date" field-name="Start Date" value="{{ $offer->start_date }}"></x-form-input>
                    </div>
                    <div class="col-md-4">
                        <x-form-input type="date" field="end_date" field-name="End Date" value="{{ $offer->end_date }}"></x-form-input>
                    </div>
                </div>

                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach (config('params.offers.status') as $value => $label)
                            <option {{ old('status', $offer->status) == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label for="show_in_suggestions">Show in Suggestions</label>
                        <input type="checkbox" name="show_in_suggestions" id="show_in_suggestions" value="1" {{ old('show_in_suggestions', $offer->show_in_suggestions) ? 'checked' : '' }}>
                    </div>
                </div>

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
