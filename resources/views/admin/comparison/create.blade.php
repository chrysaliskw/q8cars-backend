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
                    <x-form-select field="car_version_1_id" field-name="Car Version 1"
                        id="car_1_version_id"></x-form-select>
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
                            };
                        }
                    }
                });
                $('#car_id').on('select2:select', function(e) {
                    const data = e.params.data;
                    $("#car_id_text").val(data.text);
                });

                // Initialize select2 for Car 1
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

                // Initialize select2 for Car 2
                $('#car_id_2').select2({
                    placeholder: "Search Car 2",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('admin.car.select') }}",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
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

                // Initialize select2 for Car Version 1
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
                    $("#brand_1_id_text").val(data.text);
                });



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
                });

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
</x-admin-layout>
