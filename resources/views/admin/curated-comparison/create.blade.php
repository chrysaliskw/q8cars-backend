<x-admin-layout title="Curated Comparison">
    {{-- test --}}
    <link href="{{ url('moltran-asset/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.curated-comparison.index') }}">Curated Comparison</a></li>
        <li class="active">Create</li>
    </x-slot>
    <x-crud-create title="Curated Comparison">
        <x-form method="POST" action="{{ route('admin.curated-comparison.store') }}" class="form"
            enctype="multipart/form-data">

            <!-- Car 1 Section -->
            <div class="row">
                <div class="col-md-4">
                    <x-form-select field="brand_1_id" field-name="Brand 1" id="brand_1_id"></x-form-select>
                    <input type="hidden" id="brand_1_id_text" name="brand_1_id_text" />
                </div>

                <div class="col-md-4">
                    <x-form-select field="car_1_id" field-name="Compare Model 1" id="car_1_id"></x-form-select>
                    <input type="hidden" id="car_id_1_text" name="car_id_1_text" />
                </div>
                <div class="col-md-4">
                    <x-form-input type="file" field="image_1" field-name="Image 1*" value="{{ old('image_1') }}"
                        id="image_1">
                    </x-form-input>
                    <div class="col-mod-3" id="image_1-preview">
                </div>
                </div>

                    </div>

                </div>
            </div>


            <!-- Car 2 Section -->
            <div class="row">
                <div class="col-md-4">
                    <x-form-select field="brand_2_id" field-name="Brand 2" id="brand_2_id"></x-form-select>
                    <input type="hidden" id="brand_2_id_text" name="brand_2_id_text" />
                </div>

                <div class="col-md-4">
                    <x-form-select field="car_2_id" field-name="Compare Model 2" id="car_2_id"></x-form-select>
                    <input type="hidden" id="car_id_2_text" name="car_id_2_text" />
                </div>
                <div class="col-md-4">
                    <x-form-input type="file" field="image_2" field-name="Image 2" value="{{ old('image_2') }}"
                        id="image_2">
                    </x-form-input>

                    <div class="col-mod-3" id="image_2-preview">
                    </div>
                </div>
               
            </div>

            <!-- Car 3 Section -->
            <div class="row">
                <div class="col-md-4">
                    <x-form-select field="brand_3_id" field-name="Brand 3" id="brand_3_id"></x-form-select>
                    <input type="hidden" id="brand_3_id_text" name="brand_3_id_text" />
                </div>

                <div class="col-md-4">
                    <x-form-select field="car_3_id" field-name="Compare Model 3" id="car_3_id"></x-form-select>
                    <input type="hidden" id="car_id_3_text" name="car_id_3_text" />
                </div>

                <div class="col-md-4">
                    <x-form-input type="file" field="image_3" field-name="Image 3" value="{{ old('image_3') }}"
                        id="image_3">
                    </x-form-input>

                    <div class="col-mod-3" id="image_3-preview">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <x-form-input type="text" field="source" field-name="Source"
                        value="{{ old('source') }}"></x-form-textarea>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="published_date" class="control-label">Published Date*</label>

                        <div class="input-group">
                            <input type="text" name="published_date" id="published_date" class="form-control"
                                value="{{ old('published_date') }}">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="md md-event"></i></span>
                            </div>
                        </div>
                        @error('published_date')
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" id="status">
                        <option disabled selected>Select status</option>
                        @foreach (config('params.curated-comparisons.status') as $value => $label)
                            <option {{ old('status') == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                <x-form-input type="text" field="sort_order" field-name="Sort Order"
                value="{{ old('sort_order') }}"></x-form-input>
                </div>   
            </div>

            <div class="col-md-12">
                <x-form-input type="text" field="title" field-name="Title"
                    value="{{ old('title') }}"></x-form-input>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <label for="content">Content</label>
                                <div class="card-body">
                                    <textarea class="summernote form-control" rows="9" name="content">{{ old('content') }}</textarea>
                                    @error('content')
                                <span class="error" role="alert">{{ $message }}</span>
                                @enderror
                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <div class="row">


            </div>

            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-create>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <x-slot name="scripts">

        <script src="{{ url('moltran-asset/plugins/summernote/summernote-bs4.js') }}"></script>

        <script>
            jQuery(document).ready(function() {

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

            });


            $('#brand_1_id').select2({

                placeholder: "Search brand 1",
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
            $('#brand_1_id').on('select2:select', function(e) {
                const data = e.params.data;
                $("#car_id_1_text").val(data.text);

                $('#car_1_id').val(null).trigger('change');
                $('#car_id_1_text').val(null).trigger('change');

            });

            if ('{!! old('brand_1_id') !!}' && '{!! old('car_id_1_text') !!}') {
                const countryOption = new Option('{{ old('car_id_1_text') }}', '{{ old('brand_1_id') }}', true, true);
                $('#brand_1_id').append(countryOption).trigger('change');
                $("#car_id_1_text").val('{{ old('car_id_1_text') }}');
            }
            // brand 2
            $('#brand_2_id').select2({

                placeholder: "Search brand 2",
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
            $('#brand_2_id').on('select2:select', function(e) {
                const data = e.params.data;
                $("#car_id_2_text").val(data.text);

                $('#car_2_id').val(null).trigger('change');
                $('#car_id_2_text').val(null).trigger('change');
            });

            if ('{!! old('brand_2_id') !!}' && '{!! old('car_id_2_text') !!}') {
                const countryOption = new Option('{{ old('car_id_2_text') }}', '{{ old('brand_2_id') }}', true, true);
                $('#brand_2_id').append(countryOption).trigger('change');
                $("#car_id_2_text").val('{{ old('car_id_2_text') }}');
            }
            // brand 3
            $('#brand_3_id').select2({

                placeholder: "Search brand 3",
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
            $('#brand_3_id').on('select2:select', function(e) {
                const data = e.params.data;
                $("#car_id_3_text").val(data.text);

                $('#car_3_id').val(null).trigger('change');
                $('#car_id_3_text').val(null).trigger('change');
            });

            if ('{!! old('brand_3_id') !!}' && '{!! old('car_id_3_text') !!}') {
                const countryOption = new Option('{{ old('car_id_3_text') }}', '{{ old('brand_3_id') }}', true, true);
                $('#brand_3_id').append(countryOption).trigger('change');
                $("#car_id_3_text").val('{{ old('car_id_3_text') }}');
            }
            // car 1
            $('#car_1_id').select2({
                placeholder: "Search car 1",
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('admin.car.select') }}",
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                            brand_id: $('#brand_1_id').val(),
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
                }
            });
            $('#car_1_id').on('select2:select', function(e) {
                const data = e.params.data;
                $("#car_id_1_text").val(data.text);
            });

            if ('{!! old('car_1_id') !!}' && '{!! old('car_id_1_text') !!}') {
                const cOption = new Option('{{ old('car_id_1_text') }}', '{{ old('car_1_id') }}', true, true);
                $('#car_1_id').append(cOption).trigger('change');
                $("#car_id_1_text").val('{{ old('car_id_1_text') }}');
            }
            // car 2
            $('#car_2_id').select2({
                placeholder: "Search car 2",
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('admin.car.select') }}",
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                            brand_id: $('#brand_2_id').val(),
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
                }
            });
            $('#car_2_id').on('select2:select', function(e) {
                const data = e.params.data;
                $("#car_id_2_text").val(data.text);
            });
            if ('{{ old('car_2_id') }}' && '{{ old('car_id_2_text') }}') {
                const cOption = new Option('{{ old('car_id_2_text') }}', '{{ old('car_2_id') }}', true, true);
                $('#car_2_id').append(cOption).trigger('change');
                $("#car_id_2_text").val('{{ old('car_id_2_text') }}');
            }
            // car 3
            $('#car_3_id').select2({
                placeholder: "Search car 3",
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('admin.car.select') }}",
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                            brand_id: $('#brand_3_id').val(),
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
                }
            });
            $('#car_3_id').on('select2:select', function(e) {
                const data = e.params.data;
                $("#car_id_3_text").val(data.text);
            });


            if ('{{ old('car_3_id') }}' && '{{ old('car_id_3_text') }}') {
                const cOption = new Option('{{ old('car_id_3_text') }}', '{{ old('car_3_id') }}', true, true);
                $('#car_3_id').append(cOption).trigger('change');
                $("#car_id_3_text").val('{{ old('car_id_3_text') }}');
            }


            let input = document.querySelector('input#image_1');
            let input2 = document.querySelector('input#image_2');
            let input3 = document.querySelector('input#image_3');

            let preview = document.querySelector('#image_1-preview');
            let preview2 = document.querySelector('#image_2-preview');
            let preview3 = document.querySelector('#image_3-preview');

            let removeBtn = document.createElement('button');
            removeBtn.id = 'remove-image';
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-danger';
            removeBtn.style.display = 'none';
            removeBtn.innerText = 'x';
            preview.appendChild(removeBtn);

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

            input2.addEventListener('change', function(event) {
                let file = event.target.files[0];
                let reader = new FileReader();
                reader.onload = function(event) {
                    let img = document.createElement('img');
                    img.classList.add('img-thumbnail');
                    img.src = event.target.result;
                    img.width = 100;
                    img.height = 150;
                    preview2.innerHTML = '';
                    preview2.appendChild(img);
                    removeBtn.style.display = 'block';
                }
                reader.readAsDataURL(file);
            });

            input3.addEventListener('change', function(event) {
                let file = event.target.files[0];
                let reader = new FileReader();
                reader.onload = function(event) {
                    let img = document.createElement('img');
                    img.classList.add('img-thumbnail');
                    img.src = event.target.result;
                    img.width = 100;
                    img.height = 150;
                    preview3.innerHTML = '';
                    preview3.appendChild(img);
                    removeBtn.style.display = 'block';
                }
                reader.readAsDataURL(file);
            });

            $("#published_date").datepicker({
                format: 'dd-mm-yyyy',
                startDate: 'today',

            });
        </script>

    </x-slot>
</x-admin-layout>
