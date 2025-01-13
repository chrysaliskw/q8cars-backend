<x-admin-layout title="Loan Requests Reports">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Customer Reports</li>
    </x-slot>
    <x-crud-create cardTitle="Create Loan Request Report">
        <form method="GET" class="form-horizontal" action="{{ route('admin.reports.loan-requests.index') }}">
            <div class="row">


                <div class="col-md-4">
                    <x-form-select field="bank_id" field-name="Bank Name" id="bank_id" ></x-form-select>
                    <input type="hidden" id="bank_id_text" name="bank_id_text" value="<?php echo isset($_GET['bank_id_text']) ? $_GET['bank_id_text'] : ''; ?>" />
                </div>


                <div class="col-md-4">
                    <x-form-input type="text" field="name" field-name="User Name" value="<?php echo isset($_GET['name']) ? $_GET['name'] : ''; ?>"></x-form-input>
                </div>

                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status"
                    value="{{ request()->get('status', '') }}">
                    @foreach (config('params.banks.status') as $value => $label)
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
                            <a href="{{route('admin.reports.loan-requests.index')}}" class="btn btn-primary waves-effect waves-light">Reset</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </x-crud-create>

    <x-crud-index title="Loan Request Reports">
        <div class="row">
            <div class="col-lg-12">
                {!! $grid->render() !!}
            </div>
        </div>

        {{-- Hidden Form --}}
        <div class="col-lg-12" style="margin-top: 10px;">
            <div class="form-group row m-b-0">
                <div class="col-sm-10">
                    <form id="export-form" action="{{ route('admin.reports.loan-requests.export') }}" method="GET"
                        style="display: none;">
                        <input type="hidden" name="startDate" value="<?php echo $_GET['start_date'] ?? ''; ?>" />
                        <input type="hidden" name="endDate" value="<?php echo $_GET['end_date'] ?? ''; ?>" />
                        <input type="hidden" name="user_id" value="<?php echo $_GET['user_id'] ?? ''; ?>" />
                        <input type="hidden" name="name" value="<?php echo $_GET['name'] ?? ''; ?>" />
                        <input type="hidden" name="bank_id" value="<?php echo $_GET['bank_id'] ?? ''; ?>" />
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


    const oldBankId = '{{ old('bank_id') }}';
    const oldBankText = '{{ old('bank_id_text') }}';
    const getBankId =
    '{{ isset($_GET['bank_id']) ? $_GET['bank_id'] : '' }}';
    const getBankText =
    '{{ isset($_GET['bank_id_text']) ? $_GET['bank_id_text'] : '' }}';
    console.log(oldBankId);
    console.log(oldBankText);
    console.log(getBankId);
    console.log(getBankText);


            $('#bank_id').select2({

                placeholder: "Search bank name ",
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('admin.bank.select') }}",
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
                $('#bank_id').on('select2:select', function(e) {
                const data = e.params.data;
                $("#bank_id_text").val(data.text);

                });

                if (oldBankId && oldBrandText) {
                const option = new Option(oldBankText, oldBankId, true, true);
                $('#bank_id').append(option).trigger('change');
                } else
                if (getBankId && getBankText) {
                const option = new Option(getBankText, getBankId, true, true);
                $('#bank_id').append(option).trigger('change');
                }




        </script>

    </x-slot>

</x-admin-layout>
