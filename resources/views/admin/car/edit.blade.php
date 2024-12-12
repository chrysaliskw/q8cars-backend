<x-admin-layout title="Cars">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.car.index') }}">Cars</a></li>
        <li><a href="{{ route('admin.car.show', $car) }}">{{$car->model_name}}</a></li>
        <li class="active">Update</li>
    </x-slot>

    @php
        $pcount = count(config('params.professions'));
        $fcount = count(config('params.car.fuel_type'));
        $tcount = count(config('params.car.transmission_type'));
        $ccount = count(config('params.colors'));
        $trcount = count(config('params.car.travel_type'));
    @endphp
    <input type="hidden" name="pcount" id="pcount" value="{{ $pcount }}" />
    <input type="hidden" name="fcount" id="fcount" value="{{ $fcount }}" />
    <input type="hidden" name="tcount" id="tcount" value="{{ $tcount }}" />
    <input type="hidden" name="ccount" id="ccount" value="{{ $ccount }}" />
    <input type="hidden" name="trcount" id="trcount" value="{{ $trcount }}" />

    <div id="loading-spinner" style=font-size:10px>
        <!-- Loading spinner -->
    </div>

    <div id="edit-page" class="row" style="display: none;">
        <div class="col-xl-12">
            <div class="card ">
                <div class="card-header">
                    <div class="dt-buttons float-right">
                        <button type="button" onclick="onSubmit()" id="submit-btn" class="btn btn-primary buttons-copy buttons-html5 btn-md">
                            Save
                        </button>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs tabs" role="tablist" id="business-user-profile-tab">
                <li class="nav-item tab">
                    <a class="nav-link active" id="about-tab-2" data-toggle="tab" href="#about-2" role="tab"
                        onclick="onTab('about')" aria-controls="about-2" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fa fa-home"></i></span>
                        <span class="d-none d-sm-block">Basic Info</span>
                    </a>
                </li>
                <li class="nav-item tab">
                    <a class="nav-link" id="contact-tab-2" data-toggle="tab" href="#contact-2" role="tab"
                        onclick="onTab('contact')" aria-controls="contact-2" aria-selected="true">
                        <span class="d-block d-sm-none"><i class="fa fa-user"></i></span>
                        <span class="d-none d-sm-block">Summary</span>
                    </a>
                </li>
                <li class="nav-item tab">
                    <a class="nav-link" id="images-tab-2" data-toggle="tab" href="#images-2" role="tab"
                        onclick="onTab('images')" aria-controls="images-2" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fa fa-envelope-o"></i></span>
                        <span class="d-none d-sm-block">Images</span>
                    </a>
                </li>
                <li class="nav-item tab">
                    <a class="nav-link" id="videos-tab-2" data-toggle="tab" href="#videos-2" role="tab"
                        onclick="onTab('videos')" aria-controls="videos-2" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fa fa-envelope-o"></i></span>
                        <span class="d-none d-sm-block">Videos</span>
                    </a>
                </li>
                <li class="nav-item tab">
                    <a class="nav-link" id="colors-tab-2" data-toggle="tab" href="#colors-2" role="tab"
                        onclick="onTab('colors')" aria-controls="colors-2" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fa fa-envelope-o"></i></span>
                        <span class="d-none d-sm-block">Colors</span>
                    </a>
                </li>

                <li class="nav-item tab">
                    <a class="nav-link" id="additional-tab-2" data-toggle="tab" href="#additional-2" role="tab"
                        onclick="onTab('additional')" aria-controls="additional-2" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fa fa-envelope-o"></i></span>
                        <span class="d-none d-sm-block">Other Specifications</span>
                    </a>
                </li>




            </ul>

            <div class="tab-content">
                <form method="POST" id="business-user-form"
                    action="{{ route('admin.car.update', $car) }}"
                    @submit.prevent="handleSubmit(onSubmit)" class="form" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="tab-pane show active" id="about-2" role="tabpanel" aria-labelledby="about-tab-2">
                        @include('admin.car.edit-section.edit_basic_info')
                    </div>
                    <div class="tab-pane" id="contact-2" role="tabpanel" aria-labelledby="contact-tab-2">
                        @include('admin.car.edit-section.edit_summary')
                    </div>
                    <div class="tab-pane" id="images-2" role="tabpanel" aria-labelledby="images-tab-2">
                        @include('admin.car.edit-section.edit_images')
                    </div>
                    <div class="tab-pane" id="videos-2" role="tabpanel" aria-labelledby="videos-tab-2">
                        @include('admin.car.edit-section.edit_videos')
                    </div>
                    <div class="tab-pane" id="colors-2" role="tabpanel" aria-labelledby="colors-tab-2">
                        @include('admin.car.edit-section.edit_colors')
                    </div>

                    <div class="tab-pane" id="additional-2" role="tabpanel" aria-labelledby="additional-tab-2">
                        @include('admin.car.edit-section.additional')
                    </div>

                </form>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script type="text/javascript">


            $("#date_1").datepicker({
                format: 'dd-mm-yyyy',
                endDate: 'today',

            });
            $("#date_2").datepicker({
                format: 'dd-mm-yyyy',
                endDate: 'today',
            });
            $("#date_3").datepicker({
                format: 'dd-mm-yyyy',
                endDate: 'today',

            });
             $("#date_0").datepicker({
                format: 'dd-mm-yyyy',
                endDate: 'today',
            });
            if(($('#is_upcoming').val()) == 1){
        console.log($('#is_upcoming').val());
        $('.justLaunch').hide();
    }else{
        $('.justLaunch').show();

    }
    if(($('#is_just_launched').val()) == 1){
        $('#just_launch_sort_order').prop('disabled', false);
    }else{
        $('#just_launch_sort_order').prop('disabled', true);

    }
        //     function toggleFields(index)
        //    {
        //         const inputType = document.getElementById(`input_type_${index}`).value;
        //         const textField = document.getElementById(`text_value_${index}`);
        //         const booleanField = document.getElementById(`bool_value_${index}`);


        //     if (inputType == 1) { // Text
        //         textField.disabled = false;
        //         booleanField.disabled = true;
        //     } else if (inputType == 2) { // Boolean
        //         textField.disabled = true;
        //         booleanField.disabled = false;
        //     }
        // }

        function toggleFields(index)
           {
                const inputType = document.getElementById(`input_type_${index}`).value;
                const textField = document.getElementById(`text_value_${index}`);
                const booleanField = document.getElementById(`bool_value_${index}`);
                const unitField = document.getElementById(`units_${index}`);
                if (inputType == 1) { // Text
                    textField.disabled = false;
                    booleanField.disabled = true;
                    unitField.disabled = false;

                } else if (inputType == 2) { // Boolean
                    textField.disabled = true;
                    textField.value = '';
                    booleanField.disabled = false;
                    unitField.disabled = true;
                }
            }
        // Initialize visibility based on the existing input types on page load
        // document.addEventListener('DOMContentLoaded', function() {
        //     @for($i = 0; $i < 10; $i++)
        //         toggleFields({{ $i }});
        //     @endfor
        // });


            // function clearRow(button) {
            //     const row = button.closest('tr');
            //     row.querySelectorAll('input[type="text"]').forEach(input => {
            //         input.value = '';
            //     });

            //     row.querySelectorAll('select').forEach(select => {
            //         select.selectedIndex = 0;
            //     });

            //     row.querySelectorAll('input[type="hidden"]').forEach(hiddenInput => {
            //         hiddenInput.value = '';
            //     });
            //     rowCount--;
            //     document.getElementById('row_count').value = rowCount;
            // }

            function clearRow(button) {
                const rowId = button.getAttribute('data-id');
                const row = document.getElementById(`row_${rowId}`);
                if (row) {
                    row.remove();
                    rowCount--;
                    document.getElementById('row_count').value = rowCount;

                    updateSerialNumbers();
                }
            }

            function updateSerialNumbers() {
                const rows = document.querySelectorAll('#specification-rows tr');
                rows.forEach((row, index) => {
                    if (serialCell) {
                        serialCell.textContent = index + 1;
                    }
                });
            }


            /**
             * Loading spinner
             */
            function onReady(callback) {
                var intervalID = window.setInterval(checkReady, 1000);

                function checkReady() {
                    if (document.getElementsByTagName('body')[0] !== undefined) {
                        window.clearInterval(intervalID);
                        callback.call(this);
                    }
                }
            }

            function show(id, value) {
                document.getElementById(id).style.display = value ? 'block' : 'none';
            }

            onReady(function() {
                show('edit-page', true);
                show('loading-spinner', false);
            });



            function onSubmit()
            {
                document.getElementById("business-user-form").submit();
            }


            function deleteImage(id)
            {
                $("#image_preview_" + id).remove();
                $("#image_old_" + id).val('');
            }


            function selectAllProfession()
            {
                if ($("#all_profession").prop("checked")) {
                    var count = document.getElementById("pcount").value;
                    for(var i = 1; i <= count; i++) {
                        $("#professions"+i).attr("disabled", true)
                        $("#professions"+i).prop("checked", true);
                    }
                }
                else {
                    var count = document.getElementById("pcount").value;
                    for(var i = 1; i <= count; i++) {
                        $("#professions"+i).attr("disabled", false);
                        $("#professions"+i).prop("checked", false);
                    }
                }
            }

            function updateAllProfession()
            {
                var count = document.getElementById("pcount").value;
                var allChecked = true;

                for (var i = 1; i <= count; i++) {
                    if (!$("#professions" + i).prop("checked")) {
                        allChecked = false;
                        break;
                    }
                }

                $("#all_profession").prop("checked", allChecked);

                if (allChecked) {
                    for (var i = 1; i <= count; i++) {
                        $("#professions" + i).attr("disabled", true);
                    }
                }
            }

            function selectAllTravel()
            {
                if ($("#all_travel").prop("checked")) {
                    var count = document.getElementById("trcount").value;
                    for(var i = 1; i <= count; i++) {
                        $("#travel_type"+i).attr("disabled", true)
                        $("#travel_type"+i).prop("checked", true);
                    }
                }
                else {
                    var count = document.getElementById("trcount").value;
                    for(var i = 1; i <= count; i++) {
                        $("#travel_type"+i).attr("disabled", false);
                        $("#travel_type"+i).prop("checked", false);
                    }
                }
            }

            function updateAllTravel()
            {
                var count = document.getElementById("trcount").value;
                var allChecked = true;

                for (var i = 1; i <= count; i++) {
                    if (!$("#travel_type" + i).prop("checked")) {
                        allChecked = false;
                        break;
                    }
                }

                $("#all_travel").prop("checked", allChecked);

                if (allChecked) {
                    for (var i = 1; i <= count; i++) {
                        $("#travel_type" + i).attr("disabled", true);
                    }
                }
            }

            function selectAllFuels()
            {
                if ($("#all_fuels").prop("checked")) {
                    var count = document.getElementById("fcount").value;
                    for(var i = 1; i <= count; i++) {
                        $("#fuel_types"+i).attr("disabled", true)
                        $("#fuel_types"+i).prop("checked", true);
                    }
                }
                else {
                    var count = document.getElementById("fcount").value;
                    for(var i = 1; i <= count; i++) {
                        $("#fuel_types"+i).attr("disabled", false);
                        $("#fuel_types"+i).prop("checked", false);
                    }
                }
            }

            function updateAllfuels()
            {
                var count = document.getElementById("fcount").value;
                var allChecked = true;

                for (var i = 1; i <= count; i++) {
                    if (!$("#fuel_types" + i).prop("checked")) {
                        allChecked = false;
                        break;
                    }
                }

                $("#all_fuels").prop("checked", allChecked);

                if (allChecked) {
                    for (var i = 1; i <= count; i++) {
                        $("#fuel_types" + i).attr("disabled", true);
                    }
                }
            }

            function selectAllTransmissions()
            {
                if ($("#all_transmissions").prop("checked")) {
                    var count = document.getElementById("tcount").value;
                    for(var i = 1; i <= count; i++) {
                        $("#transmission_types"+i).attr("disabled", true)
                        $("#transmission_types"+i).prop("checked", true);
                    }
                }
                else {
                    var count = document.getElementById("tcount").value;
                    for(var i = 1; i <= count; i++) {
                        $("#transmission_types"+i).attr("disabled", false);
                        $("#transmission_types"+i).prop("checked", false);
                    }
                }
            }

            function updateAllTransmissions()
            {
                var count = document.getElementById("tcount").value;
                var allChecked = true;

                for (var i = 1; i <= count; i++) {
                    if (!$("#transmission_types" + i).prop("checked")) {
                        allChecked = false;
                        break;
                    }
                }

                $("#all_transmissions").prop("checked", allChecked);

                if (allChecked) {
                    for (var i = 1; i <= count; i++) {
                        $("#transmission_types" + i).attr("disabled", true);
                    }
                }
            }


            $('#body_type_id').select2({

                placeholder: "Search body type",
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('admin.body-type.select') }}",
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

            $('#body_type_id').on('select2:select', function (e) {
                const data = e.params.data;
                $("#body_type_id_text").val(data.text);
            });

            if('{!! $currentBodyType !!}') {
                const currentBodyType = JSON.parse('{!! $currentBodyType !!}');
                const bOption = new Option(currentBodyType.text, currentBodyType.id, true, true);
                $('#body_type_id').append(bOption).trigger('change');
            }

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
            });
            if('{!! $currentBrand !!}') {
                const currentBrand = JSON.parse('{!! $currentBrand !!}');
                const brandOption = new Option(currentBrand.text, currentBrand.id, true, true);
                $('#brand_id').append(brandOption).trigger('change');
            }
        </script>
        <script>
    $('#brand_id').on('change', function() {
        let brandId = $(this).val(); // Get the selected brand ID

        if (brandId) {
            $.ajax({
                url: "{{ route('admin.color.select') }}", // AJAX route
                type: "GET",
                data: {
                    brand_id: brandId
                },
                success: function(response) {
                    // Clear the existing colors
                    console.log(response);
                    $('#color-options').empty();

                    // Loop through the response to append new color options
                    $.each(response, function(key, value) {
                        let colorCheckbox = `
                            <div class="col-md-3">
                                <div class="card border-primary" style="background-color: #f0f8ff;">
                                    <div class="card-body">
                                        <div class="form-check form-check-inline col-md-12">
                                            <input class="form-check-input" type="checkbox" name="colors[]"
                                                id="colors_${key}" value="${key}">
                                            <label class="form-check-label" for="colors_${key}" style="color: black;">
                                                ${value}
                                            </label>
                                        </div>
                                        <div class="form-group" style="padding-top: 10px;">
                                            <input type="file" id="colors_image_${key}" name="colors_image_${key}">
                                        </div>
                                           <small>Image required when color is selected</small>
                                    </div>
                                </div>
                            </div>`;

                        // Append the color checkbox and file input to the color-options div
                        $('#color-options').append(colorCheckbox);
                    });
                }
            });
        } else {
            // Clear the color options if no brand is selected
            $('#color-options').empty();
        }
    });
    </script>
    </x-slot>

</x-admin-layout>
