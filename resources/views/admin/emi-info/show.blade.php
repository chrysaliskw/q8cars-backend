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
                    <x-form-select field="brand_id" field-name="Brand*" id="brand_id"></x-form-select>
                    <input type="hidden" id="brand_id_text" name="brand_id_text" />
                    <span class="error" role="alert" id="brand_id_error"></span>
                </div>
                <div class="col-md-4">
                    <x-form-select field="car_id" field-name="Car Model*" id="car_id"></x-form-select>
                    <input type="hidden" id="car_id_text" name="car_id_text" />
                </div>
                <div class="col-md-4">
                    <x-form-select field="car_version_id" field-name="Car Version" id="car_version_id"></x-form-select>
                    <input type="hidden" id="car_version_id_text" name="car_version_id_text" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <x-form-input type="text" field="principal" field-name="Principal Amount*" value="{{ old('principal') }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="loanTenureYears" field-name="Tenure(1 to 7)*" value="{{ old('loanTenureYears') }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="annualInterestRate" field-name="Annual Interest Rate(%)*" value="{{ old('annualInterestRate') }}">
                    </x-form-input>
                </div>
            </div>
            <x-form-submit>Calculate</x-form-submit>
        </div>

        <div class="card">
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
                                        <td>{{ $result['emi'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tenure</th>
                                        {{-- <td>{{ $result['year'] ?? '' }} Years</td> --}}
                                        <td>{{ isset($result['year']) && $result['year'] ? $result['year'] . ' Years' : '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Loan Principal Amount</th>
                                        <td>{{ $result['principal'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Interest Payable</th>
                                        <td>{{ $result['totalInterestPayable'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Amount Payable</th>
                                        <td>{{ $result['totalAmountPayable'] ?? '' }}</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <div class="card-header">
                            <h5>EMI Schedule</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Months</th>
                                        <th>Principal</th>
                                        <th>Interest</th>
                                      {{--  <th>Interest Percentage</th>--}}
                                        <th>EMI</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($result['schedule']) && !empty($result['schedule']))
                                        @foreach($result['schedule'] as $emi)
                                            <tr>
                                                <td>{{ $emi['months'] }}</td>
                                                <td>{{ $emi['principal'] }}</td>
                                                <td>{{ $emi['interestInCash'] }}</td>
                                               {{-- <td>{{ $emi['interest'] }}</td>--}}
                                                <td>{{ $emi['totalEmiThisYear'] }}</td>
                                                <td>{{ $emi['balance'] }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">No EMI schedule available.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-form>
    </x-crud-create>

    <x-slot name="scripts">
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

