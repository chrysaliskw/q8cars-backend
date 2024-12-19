<x-admin-layout title="Enquiries Reports">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">OEnquiries Reports</li>
    </x-slot>

    <x-crud-create cardTitle="Create Enquiries Report">
        <form method="GET" class="form-horizontal" action="{{ route('admin.reports.offer-request.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <x-form-select field="user_mobile" field-name="User Mobile" id="user_mobile"></x-form-select>
                    <input type="hidden" id="user_mobile_text" name="user_mobile_text" value="<?php echo isset($_GET['user_mobile_text']) ? $_GET['user_mobile_text'] : ''; ?>" />
                </div>
                @error('user_mobile')
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
                    <x-form-select field="type" field-name="Type" defaultPrompt="Select ">
                        <option value="" selected>All</option>
                        @foreach (config('params.offer_request.type') as $value => $label)
                            <option value="{{ $value }}"
                                {{ array_key_exists('type', $_GET) ? ($_GET['type'] == $value ? 'selected' : '') : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>

            </div>
            <div class="row">
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select ">
                        <option value="" selected>All</option>
                        @foreach (config('params.offer_request.status') as $value => $label)
                            <option value="{{ $value }}"
                                {{ array_key_exists('status', $_GET) ? ($_GET['status'] == $value ? 'selected' : '') : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
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
                            <a href="{{ route('admin.reports.offer-request.index') }}"
                                class="btn btn-primary waves-effect waves-light">Reset</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </x-crud-create>

    <x-crud-index title="Offer Request Reports">
        <div class="row">
            <div class="col-lg-12">
                {!! $grid->render() !!}
            </div>
        </div>

        {{-- Hidden Form --}}
        <div class="col-lg-12" style="margin-top: 10px;">
            <div class="form-group row m-b-0">
                <div class="col-sm-10">
                    <form id="export-form" action="{{ route('admin.reports.offer-request.export') }}" method="GET"
                        style="display: none;">
                        <input type="hidden" name="startDate" value="<?php echo $_GET['start_date'] ?? ''; ?>" />
                        <input type="hidden" name="endDate" value="<?php echo $_GET['end_date'] ?? ''; ?>" />
                        <input type="hidden" name="type" value="<?php echo $_GET['type'] ?? ''; ?>" />
                        <input type="hidden" name="user_mobile" value="<?php echo $_GET['user_mobile'] ?? ''; ?>" />
                        <input type="hidden" name="status" value="<?php echo $_GET['status'] ?? ''; ?>" />
                        <input type="hidden" name="car_1_id" value="<?php echo $_GET['car_1_id'] ?? ''; ?>" />
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

    const oldUserId = '{{ old('user_mobile') }}';
    const oldUserText = '{{ old('user_mobile_text') }}';
    const getUserId = '{{ isset($_GET['user_mobile']) ? $_GET['user_mobile'] : '' }}';
    const getUserText = '{{ isset($_GET['user_mobile_text']) ? $_GET['user_mobile_text'] : '' }}';



    console.log('the old user id',oldUserId);
    console.log( 'old user text',oldUserText);
    console.log( 'get user id',getUserId);
    console.log( 'get user text',getUserText);

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
                            // brand_id: $('#brand_1_id').val(),
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
                if (oldCarId && oldCarText) {
                const option = new Option(oldCarText, oldCarId, true, true);
                $('#car_1_id').append(option).trigger('change');
                } else
                if (getCarId && getCarText) {
                    const option = new Option(getCarText, getCarId, true, true);
                    $('#car_1_id').append(option).trigger('change');
                }

                $('#user_mobile').select2({
                placeholder: "Search User",
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('admin.user.select') }}",
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                            // brand_id: $('#brand_1_id').val(),
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
                }
            });
            $('#user_mobile').on('select2:select', function(e) {
                const data = e.params.data;
                $("#user_mobile_text").val(data.text);
            });
                if (oldUserId && oldUserText) {
                const option = new Option(oldUserText, oldUserId, true, true);
                $('#user_mobile').append(option).trigger('change');
                } else
                if (getUserId && getUserText) {
                    const option = new Option(getUserText, getUserId, true, true);
                    $('#user_mobile').append(option).trigger('change');
                }



        </script>
    </x-slot>
</x-admin-layout>
