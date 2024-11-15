<x-admin-layout title="Partner Banks">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.partner-banks.index') }}">Partner Banks</a></li>
        <li class="active">Edit</li>
    </x-slot>
    <x-crud-create title="Partner Banks">
        <x-form method="POST" action="{{ route('admin.partner-banks.update', $partner_bank) }}" class="form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-4">
                    <x-form-input type="text" field="bank_name" field-name="Bank Name" value="{{ old('bank_name', $partner_bank->bank_name) }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="branch_name" field-name="Branch Name" value="{{ old('branch_name', $partner_bank->branch_name) }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="city" field-name="City" value="{{ old('city', $partner_bank->city) }}">
                    </x-form-input>
                </div>
            </div>
            <br>
            <div class="row">
                {{-- <div class="col-md-4">
                    <x-form-input type="text" field="base_gross_income" field-name="Base Gross Income" value="{{ old('base_gross_income', $partner_bank->base_gross_income) }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="base_other_emi" field-name="Base Other EMI" value="{{ old('base_other_emi', $partner_bank->base_other_emi) }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="base_interest_rate" field-name="Base Interest Rate" value="{{ old('base_interest_rate', $partner_bank->base_interest_rate) }}">
                    </x-form-input>
                </div> --}}
                <div class="col-md-4">
                    <x-form-input type="text" field="eligible_emi_percentage" field-name="Eligible EMI Percentage" value="{{ old('eligible_emi_percentage', $partner_bank->eligible_emi_percentage) }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="file" field="logo" field-name="Logo" value="{{ old('logo') }}">
                    </x-form-input>
                    <span class="text-muted">
                        {{'Max size : 2MB, Formats : PNG,JPG,JPEG'}}
                        <br>
                        <br>
                    </span>
                    @if ($partner_bank->logo)
                        <img src="{{ $partner_bank->logo ? url(file_asset('files-banks', $partner_bank->logo)) : '' }}"
                        alt="logo" class="img-thumbnail" width="100" height="150">
                    @endif
                </div>
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach (App\Models\Bank::STATUSES as $value => $label)
                        <option {{ old('status', $partner_bank->status) == $value ? 'selected' : '' }} value="{{ $value }}">
                            {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
            </div>
            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-create>
</x-admin-layout>
