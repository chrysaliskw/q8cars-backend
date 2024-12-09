<x-admin-layout title="Compare Cars">
    <link href="{{ url('moltran-asset/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.comparison.index') }}">Compare Cars</a></li>
        <li class="active">Update</li>
    </x-slot>
    <x-form method="PUT" action="{{ route('admin.comparison.update', $carComparisonList->id) }}" class="form">
        <div class="row">
            <!-- Main Car Section -->
            <div class="col-md-4">
                <x-form-select field="page" field-name="Page" id="page" defaultPrompt="Select status">
                    @foreach (config('params.car-comparison-list.page') as $value => $label)
                        <option {{ old('page') == $value ? 'Selected' : '' }} value="{{ $value }}">
                            {{ $label }}
                        </option>
                    @endforeach
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
                <x-form-select field="car_version_1_id" field-name="Car Version 1"
                    id="car_1_version_id"></x-form-select>
                <input type="hidden" id="car_version_id_1_text" name="car_version_id_1_text" />
            </div>
        </div>

        <hr>
        {{-- @dd($currentBodyType); --}}


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
                        $('#car_id_1').val(null).trigger('change');
                    $('#car_id_1_text').val(null).trigger('change');
                    $('#car_1_version_id').val(null).trigger('change');
                    $('#car_1_version_id_text').val(null).trigger('change');
                    $('#car_id').val(null).trigger('change');
                    $('#car_id_text').val(null).trigger('change');
                    $('#car_id_2').val(null).trigger('change');
                    $('#car_id_2_text').val(null).trigger('change');
                    $('#car_2_version_id').val(null).trigger('change');
                    $('#car_2_version_id_text').val(null).trigger('change');

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


            if ('{!! $currentCarModel !!}') {
                    const currentCarModel = JSON.parse('{!! $currentCarModel !!}');
                    const car1Option = new Option(currentCarModel.text, currentCarModel.id, true, true);
                    $('#car_id').append(car1Option).trigger('change');
                }

            if ('{!! $currentCarModel1 !!}') {
                    const currentCarModel1 = JSON.parse('{!! $currentCarModel1 !!}');
                    const car1Option = new Option(currentCarModel1.text, currentCarModel1.id, true, true);
                    $('#car_id_1').append(car1Option).trigger('change');

                }

            if ('{!! $currentCarModel2 !!}') {
                    const currentCarModel2 = JSON.parse('{!! $currentCarModel2 !!}');
                    const car2Option = new Option(currentCarModel2.text, currentCarModel2.id, true, true);
                    $('#car_id_2').append(car2Option).trigger('change');
                }


            if ('{!! $currentCarVersion1 !!}') {
                    const currentCarVersion1 = JSON.parse('{!! $currentCarVersion1 !!}');
                    const carVersion1Option = new Option(currentCarVersion1.text, currentCarVersion1.id, true, true);
                    $('#car_1_version_id').append(carVersion1Option).trigger('change');
                }

            if ('{!! $currentCarVersion2 !!}') {
                const currentCarVersion2 = JSON.parse('{!! $currentCarVersion2 !!}');
                const carVersion2Option = new Option(currentCarVersion2.text, currentCarVersion2.id, true, true);
                $('#car_2_version_id').append(carVersion2Option).trigger('change');
            }
            if ('{!! $currentPage !!}') {
        const currentPage = JSON.parse('{!! $currentPage !!}');
        // Set the selected option in the dropdown based on currentPage ID
        $('#page').val(currentPage.id).trigger('change');
    }

            if ('{!! $currentbrand !!}') {
                const currentbrand = JSON.parse('{!! $currentbrand !!}');
                const brandOption = new Option(currentbrand.text, currentbrand.id, true, true);
                $('#brand_id').append(brandOption).trigger('change');

            }
            if ('{!! $currentbrand1 !!}') {
                const currentbrand1 = JSON.parse('{!! $currentbrand1 !!}');
                const brand1Option = new Option(currentbrand1.text, currentbrand1.id, true, true);
                $('#brand_1_id').append(brand1Option).trigger('change');

            }
            if ('{!! $currentbrand2 !!}') {
                const currentbrand2 = JSON.parse('{!! $currentbrand2 !!}');
                const brand2Option = new Option(currentbrand2.text, currentbrand2.id, true, true);
                $('#brand_2_id').append(brand2Option).trigger('change');

            }
            if ('{!! $currentBodyType !!}') {
                const currentBodyType = JSON.parse('{!! $currentBodyType !!}');
                const bodyTypeOption = new Option(currentBodyType.text, currentBodyType.id, true, true);
                $('#body_type_id').append(bodyTypeOption).trigger('change');
            }

            document.addEventListener('DOMContentLoaded', function () {
        const pageSelect = document.getElementById('page');
        const carSelectContainer = document.getElementById('carSelectContainer');
        const carModelContainer = document.getElementById('carModelContainer');

        function toggleCarSelect() {
            if (pageSelect.value == '2') {
                carSelectContainer.style.display = 'block';
                carModelContainer.style.display = 'block';
            } else {
                carSelectContainer.style.display = 'none';
                carModelContainer.style.display = 'none';
            }
        }

        // Run the toggle function on page load to reflect the current selection
        toggleCarSelect();

        // Add onchange event listener to the page select dropdown
        pageSelect.addEventListener('change', toggleCarSelect);
    });
        </script>
    </x-slot>
</x-admin-layout>
