<x-admin-layout title="EMI Calculator">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.emi-info.index') }}">EMI Calculator</a></li>
        <li class="active">View</li>
    </x-slot>

    <x-crud-create title="EMI Calculator">
        <x-form method="POST" action="{{ route('admin.emi-info.store') }}" class="form" enctype="multipart/form-data">
            @csrf

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <x-form-select field="brand_id" field-name="Brand*" id="brand_id">
                            <option value="{{ request()->query('brand_id') ?? old('brand_id') }}" selected>
                                {{ request()->query('brand_id_text') ?? old('brand_id_text') ?? 'Select a brand' }}
                            </option>
                        </x-form-select>
                        <input type="hidden" id="brand_id_text" name="brand_id_text" value="{{ request()->query('brand_id_text') ?? old('brand_id_text') }}"/>
                        <span class="error" role="alert" id="brand_id_error"></span>
                    </div>
                    <div class="col-md-4">
                        <x-form-select field="car_id" field-name="Car Model*" id="car_id">
                            <option value="{{ request()->query('car_id') ?? old('car_id') }}" selected>
                                {{ request()->query('car_id_text') ?? old('car_id_text') ?? 'Select a car' }}
                            </option>
                        </x-form-select>
                        <input type="hidden" id="car_id_text" name="car_id_text" value="{{ request()->query('car_id_text') ?? old('car_id_text') }}"/>
                    </div>
                    <div class="col-md-4">
                        <x-form-select field="car_version_id" field-name="Car Version" id="car_version_id">
                            <option value="{{ request()->query('car_version_id') ?? old('car_version_id') }}" selected>
                                {{ request()->query('car_version_id_text') ?? old('car_version_id_text') ?? 'Select a car version' }}
                            </option>
                        </x-form-select>
                        <input type="hidden" id="car_version_id_text" name="car_version_id_text" value="{{ request()->query('car_version_id_text') ?? old('car_version_id_text') }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <x-form-input type="text" field="principal" field-name="Principal Amount*" 
                            value="{{ request()->query('principal') ?? old('principal') }}">
                        </x-form-input>
                    </div>
                    <div class="col-md-4">
                        <x-form-input type="text" field="loanTenureYears" field-name="Tenure(1 to 7)*" 
                            value="{{ request()->query('loanTenureYears') ?? old('loanTenureYears') }}">
                        </x-form-input>
                    </div>
                    <div class="col-md-4">
                        <x-form-input type="text" field="annualInterestRate" field-name="Annual Interest Rate(%)*" 
                            value="{{ request()->query('annualInterestRate') ?? old('annualInterestRate') }}">
                        </x-form-input>
                    </div>
                </div>
                <x-form-submit>Calculate</x-form-submit>
            </div>
        </x-form>

        @if(session('result'))
        <div class="card mt-4">
            <div class="card-header">
                <h5>EMI Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>EMI</th>
                                        <td>{{ session('result')['emi'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tenure</th>
                                        <td>{{ isset(session('result')['year']) ? session('result')['year'] . ' Years' : '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Loan Principal Amount</th>
                                        <td>{{ session('result')['principal'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Interest Payable</th>
                                        <td>{{ session('result')['totalInterestPayable'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Amount Payable</th>
                                        <td>{{ session('result')['totalAmountPayable'] ?? '' }}</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <div class="card-header mt-4">
                            <h5>EMI Schedule</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Months</th>
                                        <th>Principal</th>
                                        <th>Interest</th>
                                        <th>EMI</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset(session('result')['schedule']) && !empty(session('result')['schedule']))
                                        @foreach(session('result')['schedule'] as $emi)
                                            <tr>
                                                <td>{{ $emi['months'] }}</td>
                                                <td>{{ $emi['principal'] }}</td>
                                                <td>{{ $emi['interestInCash'] }}</td>
                                                <td>{{ $emi['totalEmiThisYear'] }}</td>
                                                <td>{{ $emi['balance'] }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No EMI schedule available.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </x-crud-create>

    <x-slot name="scripts">
        <script type="application/javascript">
            $(document).ready(function() {
                // Initialize brand select2
                $('#brand_id').select2({
                    placeholder: "Search brand",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('admin.brand.select') }}",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1
                            };
                        }
                    }
                });

                $('#brand_id').on('select2:select', function(e) {
                    const data = e.params.data;
                    $("#brand_id_text").val(data.text);
                    // Reset dependent fields
                    $('#car_id').val(null).trigger('change');
                    $('#car_id_text').val('');
                    $('#car_version_id').val(null).trigger('change');
                    $('#car_version_id_text').val('');
                });

                // Initialize car select2
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
                                brand_id: $('#brand_id').val()
                            };
                        }
                    }
                });

                $('#car_id').on('select2:select', function(e) {
                    const data = e.params.data;
                    $("#car_id_text").val(data.text);
                    // Reset car version
                    $('#car_version_id').val(null).trigger('change');
                    $('#car_version_id_text').val('');
                });

                // Initialize car version select2
                $('#car_version_id').select2({
                    placeholder: "Search car version",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('admin.car-version.select') }}",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
                                car_id: $('#car_id').val()
                            };
                        }
                    }
                });

                $('#car_version_id').on('select2:select', function(e) {
                    const data = e.params.data;
                    $("#car_version_id_text").val(data.text);
                });

                // Set initial values from query parameters or old input
                @if(request()->query('brand_id') || old('brand_id'))
                    var brandId = '{{ request()->query("brand_id") ?? old("brand_id") }}';
                    var brandText = '{{ request()->query("brand_id_text") ?? old("brand_id_text") }}';
                    if (brandId && brandText) {
                        var brandOption = new Option(brandText, brandId, true, true);
                        $('#brand_id').append(brandOption).trigger('change');
                        $("#brand_id_text").val(brandText);
                    }
                @endif

                @if(request()->query('car_id') || old('car_id'))
                    var carId = '{{ request()->query("car_id") ?? old("car_id") }}';
                    var carText = '{{ request()->query("car_id_text") ?? old("car_id_text") }}';
                    if (carId && carText) {
                        var carOption = new Option(carText, carId, true, true);
                        $('#car_id').append(carOption).trigger('change');
                        $("#car_id_text").val(carText);
                    }
                @endif

                @if(request()->query('car_version_id') || old('car_version_id'))
                    var versionId = '{{ request()->query("car_version_id") ?? old("car_version_id") }}';
                    var versionText = '{{ request()->query("car_version_id_text") ?? old("car_version_id_text") }}';
                    if (versionId && versionText) {
                        var versionOption = new Option(versionText, versionId, true, true);
                        $('#car_version_id').append(versionOption).trigger('change');
                        $("#car_version_id_text").val(versionText);
                    }
                @endif
            });
        </script>
    </x-slot>
</x-admin-layout>