{{-- //working  --}}
<x-admin-layout title="Car Comparison">
    <link href="{{ url('moltran-asset/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.comparison.index') }}">Comparison</a></li>
        <li class="active">Create</li>
    </x-slot>
    <x-crud-create title="Car Comparison">
        <x-form method="POST" action="{{ route('admin.comparison.store') }}" class="form">
            <div class="row">
                <!-- Main Car Section -->
                <div class="col-md-4">
                    <x-form-select field="page" field-name="Page" id="page" onchange="toggleCarSelect()">
                        <option disabled selected value="0">Select Page</option>
                        <option value="1">Home Page</option>
                        <option value="2">Detailed Page</option>
                        <option value="3">Comparison Page</option>
                    </x-form-select>
                </div>

                <div class="col-md-4">
                    <x-form-select field="body_type_id" field-name="Body Type" id="body_type_id"></x-form-select>
                    <input type="hidden" id="body_type_id_text" name="body_type_id_text" />
                </div>
            </div>
            <hr>


            <div class="row">
                <div class="col-md-4" id="carSelectContainer"
                    style=" @if ($errors->has('brand_id') || $errors->has('car_id')) display: block; @else display: none; @endif;">
                    <x-form-select field="brand_id" field-name="Main Brand" id="brand_id"></x-form-select>
                    <input type="hidden" id="brand_id_text" name="brand_id_text" />
                </div>

                <div class="col-md-4" id="carModelContainer"
                    style="@if ($errors->has('car_id') || $errors->has('brand_id')) display: block; @else display: none; @endif">
                    <x-form-select field="car_id" field-name="Main Car Model" id="car_id"></x-form-select>
                    <input type="hidden" id="car_id_text" name="car_id_text" />
                </div>
            </div>


            <div class="row">
                <!-- Car 1 Section -->
                <div class="col-md-4">
                    <x-form-select field="brand_1_id" field-name="Brand 1" id="brand_1_id"></x-form-select>
                    <input type="hidden" id="brand_1_id_text" name="brand_1_id_text" />
                </div>

                <div class="col-md-4">
                    <x-form-select field="car_id_1" field-name="Compare Model 1" id="car_id_1"></x-form-select>
                    <input type="hidden" id="car_id_1_text" name="car_id_1_text" />
                </div>

                <div class="col-md-4">
                    <x-form-select field="car_1_version_id" field-name="Car Version 1"
                        id="car_1_version_id"></x-form-select>
                    <input type="hidden" id="car_version_id_1_text" name="car_version_id_1_text" />
                </div>
            </div>
            @if (session('error'))
                <div class="alert alert-danger">
                    {!! session('error') !!}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {!! $error !!}<br>
                    @endforeach
                </div>
            @endif

            <hr>

            <div class="row">
                <!-- Car 2 Section -->
                <div class="col-md-4">
                    <x-form-select field="brand_2_id" field-name="Brand 2" id="brand_2_id"></x-form-select>
                    <input type="hidden" id="brand_2_id_text" name="brand_2_id_text" />
                </div>

                <div class="col-md-4">
                    <x-form-select field="car_id_2" field-name="Compare Model 2" id="car_id_2"></x-form-select>
                    <input type="hidden" id="car_id_2_text" name="car_id_2_text" />
                </div>

                <div class="col-md-4">
                    <x-form-select field="car_2_version_id" field-name="Car Version 2"
                        id="car_2_version_id"></x-form-select>
                    <input type="hidden" id="car_2_version_id_text" name="car_2_version_id_text" />
                </div>
            </div>

            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-create>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <x-slot name="scripts">
        <script src="{{ url('moltran-asset/plugins/summernote/summernote-bs4.js') }}"></script>
        <script>
            $(document).ready(function() {

                $(document).ready(function() {
                    $('#body_type_id').select2({
                        placeholder: "Search Body Type",
                        minimumInputLength: 1,
                        ajax: {
                            url: "{{ route('admin.body-type.select') }}",
                            dataType: 'json',
                            data: function(params) {
                                return {
                                    search: params.term,
                                    page: params.page || 1,
                                };
                            }
                        }
                    });

                    $('#body_type_id').on('select2:select', function(e) {
                        const data = e.params.data;
                        $("#body_type_id_text").val(data.text);
                    });
                });



                $('#brand_id').select2({
                    placeholder: "Search Brand",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('admin.brand.select') }}",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
                            };
                        }
                    }
                });
                $('#brand_id').on('select2:select', function(e) {
                    const data = e.params.data;
                    $("#brand_id_text").val(data.text);

                    $('#car_id').val(null).trigger('change');
                    $('#car_id_text').val(null).trigger('change');
                    // $('#car_1_version_id').val(null).trigger('change');
                    // $('#car_1_version_id_text').val(null).trigger('change');
                });
                // Initialize select2 for the main car
                $('#car_id').select2({
                    placeholder: "Search car",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('admin.car.select') }}",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
                                brand_id: $('#brand_id').val(),
                            };
                        }
                    }
                });
                $('#car_id').on('select2:select', function(e) {
                    const data = e.params.data;
                    $("#car_id_text").val(data.text);
                    $('#car_1_version_id').val(null).trigger('change');
                    $('#car_1_version_id_text').val(null).trigger('change');
                });

                // Initialize select2 for Car Version 1


                //car 1
                $('#brand_1_id').select2({
                    placeholder: "Search Brand",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('admin.brand.select') }}",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
                            };
                        }
                    }
                });
                $('#brand_1_id').on('select2:select', function(e) {
                    const data = e.params.data;
                    $("#brand_1_id_text").val(data.text);
                    $('#car_id_1').val(null).trigger('change');
                    $('#car_id_1_text').val(null).trigger('change');
                    $('#car_1_version_id').val(null).trigger('change');
                    $('#car_1_version_id_text').val(null).trigger('change');
                });

                $('#car_id_1').select2({
                    placeholder: "Search Car 1",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('admin.car-comparison-lists.select') }}",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
                                brand_id: $('#brand_1_id').val(),
                                body_type: $('#body_type_id').val(),
                            };

                        }
                    }
                });
                $('#car_id_1').on('select2:select', function(e) {
                    const data = e.params.data;
                    $("#car_id_1_text").val(data.text);
                    // Reset Car Version 1 when Car 1 changes
                    $('#car_1_version_id').val(null).trigger('change');
                    $('#car_version_id_1_text').val(null);
                });

                $('#car_1_version_id').select2({
                    placeholder: "Search Car version 1",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('admin.car-version.select') }}",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
                                car_id: $('#car_id_1').val(),
                            };
                        }
                    }
                });
                $('#car_1_version_id').on('select2:select', function(e) {
                    const data = e.params.data;
                    $("#car_version_id_1_text").val(data.text);
                });


                //car3

                $('#brand_2_id').select2({
                    placeholder: "Search Brand",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('admin.brand.select') }}",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
                            };
                        }
                    }
                });
                $('#brand_2_id').on('select2:select', function(e) {
                    const data = e.params.data;
                    $("#brand_2_id_text").val(data.text);

                    $('#car_id_2').val(null).trigger('change');
                    $('#car_id_2_text').val(null).trigger('change');
                    $('#car_2_version_id').val(null).trigger('change');
                    $('#car_2_version_id_text').val(null).trigger('change');
                });

                $('#car_id_2').select2({
                    placeholder: "Search Car 2",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('admin.car-comparison-lists.select') }}",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
                                brand_id: $('#brand_2_id').val(),
                                body_type: $('#body_type_id').val(),
                            };
                        }
                    }
                });
                $('#car_id_2').on('select2:select', function(e) {
                    const data = e.params.data;
                    $("#car_id_2_text").val(data.text);
                    // Reset Car 2 Version when Car 2 changes
                    $('#car_2_version_id').val(null).trigger('change');
                    $('#car_2_version_id_text').val(null);
                });

                // Initialize select2 for Car Version 2
                $('#car_2_version_id').select2({
                    placeholder: "Search Car version 2",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('admin.car-version.select') }}",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
                                car_id: $('#car_id_2').val(),
                            };
                        }
                    }
                });
                $('#car_2_version_id').on('select2:select', function(e) {
                    const data = e.params.data;
                    $("#car_2_version_id_text").val(data.text);
                });


                if ('{!! old('body_type_id') !!}' && '{!! old('body_type_id_text') !!}') {
                    const bOption = new Option('{{ old('body_type_id_text') }}', '{{ old('body_type_id') }}',
                        true, true);
                    $('#body_type_id').append(bOption).trigger('change');
                    $("#body_type_id_text").val('{{ old('body_type_id_text') }}');
                }

                if ('{!! old('brand_id') !!}' && '{!! old('brand_id_text') !!}') {
                    const countryOption = new Option('{{ old('brand_id_text') }}', '{{ old('brand_id') }}', true,
                        true);
                    $('#brand_id').append(countryOption).trigger('change');
                    $("#brand_id_text").val('{{ old('brand_id_text') }}');
                }
                if ('{!! old('car_id') !!}' && '{!! old('car_id_text') !!}') {
                    const cOption = new Option('{{ old('car_id_text') }}', '{{ old('car_id') }}', true, true);
                    $('#car_id').append(cOption).trigger('change');
                    $("#car_id_text").val('{{ old('car_id_text') }}');
                }


                if ('{!! old('brand_1_id') !!}' && '{!! old('brand_1_id_text') !!}') {
                    const countryOption = new Option('{{ old('brand_1_id_text') }}', '{{ old('brand_1_id') }}', true,
                        true);
                    $('#brand_1_id').append(countryOption).trigger('change');
                    $("#brand_1_id_text").val('{{ old('brand_1_id_text') }}');
                }
                if ('{!! old('car_id_1') !!}' && '{!! old('car_id_1_text') !!}') {
                    const cOption = new Option('{{ old('car_id_1_text') }}', '{{ old('car_id_1') }}', true, true);
                    $('#car_id_1').append(cOption).trigger('change');
                    $("#car_id_1_text").val('{{ old('car_id_1_text') }}');
                }
                if ('{!! old('car_1_version_id') !!}' && '{!! old('car_version_id_1_text') !!}') {
                    const vOption = new Option('{{ old('car_version_id_1_text') }}', '{{ old('car_1_version_id') }}',
                        true, true);
                    $('#car_1_version_id').append(vOption).trigger('change');
                    $("#car_version_id_1_text").val('{{ old('car_version_id_1_text') }}');
                }


                if ('{!! old('brand_2_id') !!}' && '{!! old('brand_2_id_text') !!}') {
                    const countryOption = new Option('{{ old('brand_2_id_text') }}', '{{ old('brand_2_id') }}', true,
                        true);
                    $('#brand_2_id').append(countryOption).trigger('change');
                    $("#brand_2_id_text").val('{{ old('brand_2_id_text') }}');
                }
                if ('{!! old('car_id_2') !!}' && '{!! old('car_id_2_text') !!}') {
                    const cOption = new Option('{{ old('car_id_2_text') }}', '{{ old('car_id_2') }}', true, true);
                    $('#car_id_2').append(cOption).trigger('change');
                    $("#car_id_2_text").val('{{ old('car_id_2_text') }}');
                }
                if ('{!! old('car_2_version_id') !!}' && '{!! old('car_2_version_id_text') !!}') {
                    const vOption = new Option('{{ old('car_2_version_id_text') }}', '{{ old('car_2_version_id') }}',
                        true, true);
                    $('#car_2_version_id').append(vOption).trigger('change');
                    $("#car_2_version_id_text").val('{{ old('car_2_version_id_text') }}');
                }

                if ('{!! old('page') !!}' && '{!! old('page_text') !!}') {
                    const pOption = new Option('{{ old('page_text') }}', '{{ old('page') }}', true, true);
                    $('#page').append(pOption).trigger('change');
                    $("#page_text").val('{{ old('page_text') }}');
                }








            });


            function toggleCarSelect() {
                const pageSelect = document.getElementById('page');
                const carSelectContainer = document.getElementById('carSelectContainer');
                const carModelContainer = document.getElementById('carModelContainer');

                // Show the car select containers if "Detailed Page" is selected
                if (pageSelect.value == '2') {
                    carSelectContainer.style.display = 'block';
                    carModelContainer.style.display = 'block';
                } else {
                    carSelectContainer.style.display = 'none';
                    carModelContainer.style.display = 'none';
                }
            }
        </script>
    </x-slot>
</x-admin-layout>



{{-- <x-admin-layout title="Car Comparison">
    <link href="{{ url('moltran-asset/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.comparison.index') }}">Comparison</a></li>
        <li class="active">Create</li>
    </x-slot>
    <x-crud-create title="Car Comparison">
        <x-form method="POST" action="{{ route('admin.comparison.store') }}" class="form">
            <div class="row">
                <div class="col-md-4">
                    <x-form-select field="page" field-name="Page" id="page" onchange="toggleCarSelect()">
                        <option disabled selected value="0">Select Page</option>
                        <option value="1">Home Page</option>
                        <option value="2">Detailed Page</option>
                    </x-form-select>
                </div>

                <div class="col-md-4">
                    <x-form-select field="body_type_id" field-name="Body Type" id="body_type_id"></x-form-select>
                    <input type="hidden" id="body_type_id_text" name="body_type_id_text" />
                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-md-4" id="carSelectContainer" style="display: none;">
                    <x-form-select field="brand_id" field-name="Main Brand" id="brand_id"></x-form-select>
                    <input type="hidden" id="brand_id_text" name="brand_id_text" />
                </div>

                <div class="col-md-4" id="carModelContainer" style="display: none;">
                    <x-form-select field="car_id" field-name="Main Car Model" id="car_id"></x-form-select>
                    <input type="hidden" id="car_id_text" name="car_id_text" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <x-form-select field="brand_1_id" field-name="Brand 1" id="brand_1_id"></x-form-select>
                    <input type="hidden" id="brand_1_id_text" name="brand_1_id_text" />
                </div>

                <div class="col-md-4">
                    <x-form-select field="car_id_1" field-name="Compare Model 1" id="car_id_1"></x-form-select>
                    <input type="hidden" id="car_id_1_text" name="car_id_1_text" />
                </div>

                <div class="col-md-4">
                    <x-form-select field="car_version_1_id" field-name="Car Version 1" id="car_1_version_id"></x-form-select>
                    <input type="hidden" id="car_version_id_1_text" name="car_version_id_1_text" />
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-4">
                    <x-form-select field="brand_2_id" field-name="Brand 2" id="brand_2_id"></x-form-select>
                    <input type="hidden" id="brand_2_id_text" name="brand_2_id_text" />
                </div>

                <div class="col-md-4">
                    <x-form-select field="car_id_2" field-name="Compare Model 2" id="car_id_2"></x-form-select>
                    <input type="hidden" id="car_id_2_text" name="car_id_2_text" />
                </div>

                <div class="col-md-4">
                    <x-form-select field="car_2_version_id" field-name="Car Version 2" id="car_2_version_id"></x-form-select>
                    <input type="hidden" id="car_2_version_id_text" name="car_2_version_id_text" />
                </div>
            </div>

            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-create>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <x-slot name="scripts">
        <script src="{{ url('moltran-asset/plugins/summernote/summernote-bs4.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('#body_type_id').select2({
                    placeholder: "Search Body Type",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('admin.body-type.select') }}",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
                            };
                        }
                    }
                });

                $('#body_type_id').on('select2:select', function(e) {
                    const data = e.params.data;
                    $("#body_type_id_text").val(data.text);
                    fetchCarModels(data.id); // Fetch car models based on body type
                    console.log(data    );
                });

                function fetchCarModels(bodyTypeId) {
                    const fetchOptions = {
                        url: "{{ route('admin.car-comparison-lists.select') }}", // Adjust route as needed
                        data: { body_type_id: bodyTypeId },
                        dataType: 'json',
                        success: function(data) {
                            // Populate car model selects
                            const carSelects = ['#car_id', '#car_id_1', '#car_id_2'];
                            carSelects.forEach(function(select) {
                                $(select).empty().select2({
                                    data: data,
                                    placeholder: "Select Car Model"
                                });
                            });
                        }
                    };

                    // Clear existing selections
                    $('#car_id').val(null).trigger('change');
                    $('#car_id_1').val(null).trigger('change');
                    $('#car_id_2').val(null).trigger('change');

                    // Fetch filtered car models
                    $.ajax(fetchOptions);
                }

                // Initialize select2 for Main Brand and car models as needed
                $('#brand_id, #brand_1_id, #brand_2_id').select2({
                    placeholder: "Search Brand",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('admin.brand.select') }}",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
                            };
                        }
                    }
                }).on('select2:select', function(e) {
                    const data = e.params.data;
                    const brandTextId = $(this).attr('id') + '_text';
                    $("#" + brandTextId).val(data.text);
                    
                    // Clear car selections when brand changes
                    const carSelectId = $(this).attr('id').replace('brand_', 'car_');
                    $("#" + carSelectId).val(null).trigger('change');
                });

                // Initialize car version selects
                $('#car_1_version_id, #car_2_version_id').select2({
                    placeholder: "Search Car Version",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('admin.car-version.select') }}",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
                                car_id: $(this).val(), // Use selected car ID
                            };
                        }
                    }
                }).on('select2:select', function(e) {
                    const data = e.params.data;
                    const versionTextId = $(this).attr('id') + '_text';
                    $("#" + versionTextId).val(data.text);
                });
            });

            function toggleCarSelect() {
                const pageSelect = document.getElementById('page');
                const carSelectContainer = document.getElementById('carSelectContainer');
                const carModelContainer = document.getElementById('carModelContainer');

                // Show the car select containers if "Detailed Page" is selected
                if (pageSelect.value == '2') {
                    carSelectContainer.style.display = 'block';
                    carModelContainer.style.display = 'block';
                } else {
                    carSelectContainer.style.display = 'none';
                    carModelContainer.style.display = 'none';
                }
            }
        </script>
    </x-slot>
</x-admin-layout> --}}

{{-- 
<x-admin-layout title="Car Comparison">
    <link href="{{ url('moltran-asset/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.comparison.index') }}">Comparison</a></li>
        <li class="active">Create</li>
    </x-slot>
    <x-crud-create title="Car Comparison">
        <x-form method="POST" action="{{ route('admin.comparison.store') }}" class="form">
            <div class="row">
                <!-- Main Car Section -->
                <div class="col-md-4">
                    <x-form-select field="page" field-name="Page" id="page" onchange="toggleCarSelect()">
                        <option disabled selected value="0">Select Page</option>
                        <option value="1">Home Page</option>
                        <option value="2">Detailed Page</option>
                    </x-form-select>
                </div>

                <div class="col-md-4">
                    <x-form-select field="body_type_id" field-name="Body Type" id="body_type_id"></x-form-select>
                    <input type="hidden" id="body_type_id_text" name="body_type_id_text" />
                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-md-4" id="carSelectContainer" style="display: none;">
                    <x-form-select field="brand_id" field-name="Main Brand" id="brand_id"></x-form-select>
                    <input type="hidden" id="brand_id_text" name="brand_id_text" />
                </div>

                <div class="col-md-4" id="carModelContainer" style="display: none;">
                    <x-form-select field="car_id" field-name="Main Car Model" id="car_id"></x-form-select>
                    <input type="hidden" id="car_id_text" name="car_id_text" />
                </div>
            </div>

            <div class="row">
                <!-- Car 1 Section -->
                <div class="col-md-4">
                    <x-form-select field="brand_1_id" field-name="Brand 1" id="brand_1_id"></x-form-select>
                    <input type="hidden" id="brand_1_id_text" name="brand_1_id_text" />
                </div>

                <div class="col-md-4">
                    <x-form-select field="car_id_1" field-name="Compare Model 1" id="car_id_1"></x-form-select>
                    <input type="hidden" id="car_id_1_text" name="car_id_1_text" />
                </div>

                <div class="col-md-4">
                    <x-form-select field="car_version_1_id" field-name="Car Version 1" id="car_1_version_id"></x-form-select>
                    <input type="hidden" id="car_version_id_1_text" name="car_version_id_1_text" />
                </div>
            </div>

            <hr>

            <div class="row">
                <!-- Car 2 Section -->
                <div class="col-md-4">
                    <x-form-select field="brand_2_id" field-name="Brand 2" id="brand_2_id"></x-form-select>
                    <input type="hidden" id="brand_2_id_text" name="brand_2_id_text" />
                </div>

                <div class="col-md-4">
                    <x-form-select field="car_id_2" field-name="Compare Model 2" id="car_id_2"></x-form-select>
                    <input type="hidden" id="car_id_2_text" name="car_id_2_text" />
                </div>

                <div class="col-md-4">
                    <x-form-select field="car_2_version_id" field-name="Car Version 2" id="car_2_version_id"></x-form-select>
                    <input type="hidden" id="car_2_version_id_text" name="car_2_version_id_text" />
                </div>
            </div>

            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-create>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <x-slot name="scripts">
        <script src="{{ url('moltran-asset/plugins/summernote/summernote-bs4.js') }}"></script>
      
        <script>
            $(document).ready(function() {
                let selectedBodyTypeId = null;
        
                // Initialize Select2 for Body Type
                $('#body_type_id').select2({
                    placeholder: "Search Body Type",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('admin.body-type.select') }}",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
                            };
                        }
                    }
                });
        
                $('#body_type_id').on('select2:select', function(e) {
                    const data = e.params.data;
                    $("#body_type_id_text").val(data.text);
                    selectedBodyTypeId = data.id; // Store selected body type ID
                    updateBrandSelect(); // Update brands based on selected body type
                });
        
                function updateBrandSelect() {
                    $('#brand_id').select2({
                        placeholder: "Search Brand",
                        minimumInputLength: 1,
                        ajax: {
                            url: "{{ route('admin.brand.select') }}",
                            dataType: 'json',
                            data: function(params) {
                                return {
                                    search: params.term,
                                    page: params.page || 1,
                                    body_type_id: selectedBodyTypeId // Pass selected body type ID
                                };
                            }
                        }
                    });
                }
        
                // Brand selection logic
                $('#brand_id').on('select2:select', function(e) {
                    const data = e.params.data;
                    $("#brand_id_text").val(data.text);
        
                    // Reset dependent selects
                    $('#car_id').val(null).trigger('change');
                    $('#car_id_text').val(null);
        
                    updateCarModelSelect(data.id); // Update car model based on selected brand
                });
        
                function updateCarModelSelect(brandId) {
                    $('#car_id').select2({
                        placeholder: "Search car",
                        minimumInputLength: 1,
                        ajax: {
                            url: "{{ route('admin.car.select') }}",
                            dataType: 'json',
                            data: function(params) {
                                return {
                                    search: params.term,
                                    page: params.page || 1,
                                    brand_id: brandId,
                                    body_type_id: selectedBodyTypeId // Include body type for filtering
                                };
                            }
                        }
                    });
                }
        
                // Other sections (Car 1 and Car 2) would be similarly adjusted...
        
                // Example for Car 1
                $('#brand_1_id').on('select2:select', function(e) {
                    const data = e.params.data;
                    $("#brand_1_id_text").val(data.text);
                    updateCarModelSelectForCar1(data.id); // Update car model for Car 1
                });
        
                function updateCarModelSelectForCar1(brandId) {
                    $('#car_id_1').select2({
                        placeholder: "Search Car 1",
                        minimumInputLength: 1,
                        ajax: {
                            url: "{{ route('admin.car.select') }}",
                            dataType: 'json',
                            data: function(params) {
                                return {
                                    search: params.term,
                                    page: params.page || 1,
                                    brand_id: brandId,
                                    body_type_id: selectedBodyTypeId // Include body type for filtering
                                };
                            }
                        }
                    });
                }
        
                // Similar updates for Car 2...
            });
        </script>
        
    </x-slot>
</x-admin-layout> --}}
