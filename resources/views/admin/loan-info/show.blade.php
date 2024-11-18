<x-admin-layout title="Loan Eligibility Calculator">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.loan-info.index') }}">Loan Eligibility Calculator</a></li>
        <li class="active">View</li>
    </x-slot>
    <x-crud-create title="Loan Eligibility Calculator">
        <x-form method="POST" action="{{ route('admin.loan-info.store') }}" class="form" enctype="multipart/form-data">
            @csrf

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <x-form-select field="bank_id" field-name="Bank*" defaultPrompt="Select bank">
                        @foreach ($banks as $bank)
                            <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                {{ $bank->bank_name }}
                            </option>
                        @endforeach
                    </x-form-select>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="base_gross_income" field-name="Gross Income*" value="{{ old('base_gross_income') }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="base_other_emi" field-name="Other EMIs" value="{{ old('base_other_emi') }}">
                    </x-form-input>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <x-form-input type="text" field="base_interest_rate" field-name="Annual Interest Rate(%)*" value="{{ old('base_interest_rate') }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="loanTenureYears" field-name="Tenure(1 to 7)*" value="{{ old('loanTenureYears') }}">
                    </x-form-input>
                </div>
            </div>
            <x-form-submit>Calculate</x-form-submit>
        </div>

        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h5>Loan Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Bank Name</th>
                                            <td>{{ $loanEligibility['bank_name'] ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Max Loan Amount</th>
                                            <td>{{ $emiService['maxLoanAmount'] ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Max EMI</th>
                                            <td>{{ $loanEligibility['eligibleEmi'] ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Interest Payable</th>
                                            <td>{{ isset($loanEligibility['interestRate']) ? $loanEligibility['interestRate'] : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Eligibility</th>
                                            <td>{{ $loanEligibility['eligibility'] ?? '' }}</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <div class="container">
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
                                                <td>{{ $emiService['emi'] ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tenure</th>
                                                <td>{{ isset($emiService['year']) && $emiService['year'] ? $emiService['year'] . ' Years' : '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Loan Principal Amount</th>
                                                <td>{{ $emiService['maxLoanAmount'] ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Interest Payable</th>
                                                <td>{{ $emiService['totalInterestPayable'] ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Amount Payable</th>
                                                <td>{{ $emiService['totalAmountPayable'] ?? '' }}</td>
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
                                            @if(isset($emiService['schedule']) && !empty($emiService['schedule']))
                                                @foreach($emiService['schedule'] as $emi)
                                                    <tr>
                                                        <td>{{ $emi['months'] }}</td>
                                                        <td>{{ $emi['base_gross_income'] }}</td>
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
            </div>

    </x-form>
    </x-crud-create>


</x-admin-layout>

