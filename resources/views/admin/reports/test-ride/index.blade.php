<x-admin-layout title="Test Ride Request Reports">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Customer Reports</li>
    </x-slot>

    <x-crud-create cardTitle="Create Test Ride Report">
        <form method="GET" class="form-horizontal" action="{{ route('admin.reports.test-ride.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <x-form-select field="brand_1_id" field-name="Car Brand" id="brand_1_id"></x-form-select>
                    <input type="hidden" id="brand_1_id_text" name="brand_1_id_text" value="<?php echo isset($_GET['brand_1_id_text']) ? $_GET['brand_1_id_text'] : ''; ?>" />
                </div>
                @error('brand_1_id')
                    <span class="error" role="alert">{{ $message }}</span>
                @enderror

                <div class="col-md-4">
                    <x-form-select field="car_1_id" field-name="Car Model" id="car_1_id"></x-form-select>
                    <input type="hidden" id="car_id_1_text" name="car_id_1_text" value="<?php echo isset($_GET['car_id_1_text']) ? $_GET['car_id_1_text'] : ''; ?>" />
                </div>
                @error('car_1_id')
                    <span class="error" role="alert">{{ $message }}</span>
                @enderror

                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status"
                        value="{{ request()->get('status', '') }}">
                        @php
                       $status = config('params.test_drive.status');
                       unset($status[5]);
                        @endphp
                        @foreach ($status as $value => $label)
                            <option {{ old('status') == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}
                            </option>
                        @endforeach
                    </x-form-select>
                </div>
            </div>


            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_date" class="control-label">Start date</label>
                        <div class="input-group">
                            <input type="text" name="start_date" id="start_date" class="form-control"
                                value="<?php echo $_GET['start_date'] ?? ''; ?>">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="md md-event"></i></span>
                            </div>
                        </div>
                        @error('start_date')
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                        @error('startDate')
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_date" class="control-label">End date</label>
                        <div class="input-group">
                            <input type="text" name="end_date" id="end_date" class="form-control"
                                value="<?php echo $_GET['end_date'] ?? ''; ?>">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="md md-event"></i></span>
                            </div>
                        </div>
                        @error('end_date')
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                        @error('endDate')
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="d-flex align-items-center" style="height:100%;padding:0 10px;">
                        <div class="action-item-button generate-btn"style="margin-right:10px;">
                            <x-form-submit>Generate Report</x-form-submit>
                        </div>
                        <div class="action-item-button"style="margin-right:10px;">
                            <button type="submit" class="btn btn-primary waves-effect waves-light"
                                onclick="event.preventDefault();document.getElementById('export-form').submit();">
                                <i class="fa fa-download"></i> Download
                            </button>
                        </div>
                        <div class="action-item-button">
                            <a href="{{ route('admin.reports.test-ride.index') }}"
                                class="btn btn-primary waves-effect waves-light">Reset</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </x-crud-create>

    <x-crud-index title="Test Ride Reports">
        <div class="row">
            <div class="col-lg-12">
                {!! $grid->render() !!}
            </div>
        </div>

        {{-- Hidden Form --}}
        <div class="col-lg-12" style="margin-top: 10px;">
            <div class="form-group row m-b-0">
                <div class="col-sm-10">
                    <form id="export-form" action="{{ route('admin.reports.test-ride.export') }}" method="GET"
                        style="display: none;">
                        <input type="hidden" name="startDate" value="<?php echo $_GET['start_date'] ?? ''; ?>" />
                        <input type="hidden" name="endDate" value="<?php echo $_GET['end_date'] ?? ''; ?>" />
                        <input type="hidden" name="user_id" value="<?php echo $_GET['user_id'] ?? ''; ?>" />
                        <input type="hidden" name="car_1_id" value="<?php echo $_GET['car_1_id'] ?? ''; ?>" />
                        <input type="hidden" name="brand_1_id" value="<?php echo $_GET['brand_1_id'] ?? ''; ?>" />
                        <input type="hidden" name="status" value="<?php echo $_GET['status'] ?? ''; ?>" />
                        <input type="hidden" name="area_id" value="<?php echo $_GET['area_id'] ?? ''; ?>" />
                    </form>
                </div>
            </div>
        </div>
        {{-- Hidden Form End --}}
    </x-crud-index>


    <x-slot name="scripts">

        <script type="application/javascript">

            $("#start_date").datepicker({
                format: 'dd-mm-yyyy',
                orientation: 'bottom',
            });

            $("#end_date").datepicker({
                format: 'dd-mm-yyyy',
                orientation: 'bottom',
            });


    const oldBrandId = '{{ old('brand_1_id') }}';
    const oldBrandText = '{{ old('brand_1_id_text') }}';
    const getBrandId = '{{ isset($_GET['brand_1_id']) ? $_GET['brand_1_id'] : '' }}';
    const getBrandText = '{{ isset($_GET['brand_1_id_text']) ? $_GET['brand_1_id_text'] : '' }}';
    const oldCarId = '{{ old('car_1_id') }}';
    const oldCarText = '{{ old('car_id_1_text') }}';
    const getCarId = '{{ isset($_GET['car_1_id']) ? $_GET['car_1_id'] : '' }}';
    const getCarText = '{{ isset($_GET['car_id_1_text']) ? $_GET['car_id_1_text'] : '' }}';

    console.log(oldBrandId);
    console.log(oldBrandText);


            $('#brand_1_id').select2({

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
                $('#brand_1_id').on('select2:select', function(e) {
                const data = e.params.data;
                $("#brand_1_id_text").val(data.text);

                $('#car_1_id').val(null).trigger('change');
                $('#car_id_1_text').val(null).trigger('change');

                });

            if (oldBrandId && oldBrandText) {
        const option = new Option(oldBrandText, oldBrandId, true, true);
        $('#brand_1_id').append(option).trigger('change');
    } else if (getBrandId && getBrandText) {
        const option = new Option(getBrandText, getBrandId, true, true);
        $('#brand_1_id').append(option).trigger('change');
    }

            $('#car_1_id').select2({
                placeholder: "Search car",
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

            // if ('{!! old('car_1_id') !!}' && '{!! old('car_id_1_text') !!}') {
            //     const cOption = new Option('{{ old('car_id_1_text') }}', '{{ old('car_1_id') }}', true, true);
            //     $('#car_1_id').append(cOption).trigger('change');
            //     $("#car_id_1_text").val('{{ old('car_id_1_text') }}');
            // }
            if (oldCarId && oldCarText) {
        const option = new Option(oldCarText, oldCarId, true, true);
        $('#car_1_id').append(option).trigger('change');
    } else if (getCarId && getCarText) {
        const option = new Option(getCarText, getCarId, true, true);
        $('#car_1_id').append(option).trigger('change');
    }



        </script>

    </x-slot>

</x-admin-layout>
