<x-admin-layout title="Partner Banks">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.partner-banks.index') }}">Partner Banks</a></li>
        <li class="active">Create</li>
    </x-slot>
    <x-crud-create title="Partner Banks">
        <x-form method="POST" action="{{ route('admin.partner-banks.store') }}" class="form" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-4">
                    <x-form-input type="text" field="bank_name" field-name="Bank Name" value="{{ old('bank_name') }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="branch_name" field-name="Branch Name" value="{{ old('branch_name') }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="city" field-name="City" value="{{ old('city') }}">
                    </x-form-input>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <x-form-input type="text" field="base_gross_income" field-name="Base Gross Income" value="{{ old('base_gross_income') }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="base_other_emi" field-name="Base Other EMI" value="{{ old('base_other_emi') }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="base_interest_rate" field-name="Base Interest Rate" value="{{ old('base_interest_rate') }}">
                    </x-form-input>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <x-form-input type="file" field="logo" field-name="Logo" value="{{ old('logo') }}">
                    </x-form-input>
                    <span class="text-muted">
                        {{'Max size : 2MB, Formats : PNG,JPG,JPEG'}}
                        <br>
                        <br>
                    </span>
                </div>
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach (App\Models\Bank::STATUSES as $value => $label)
                        <option {{ old('status') == $value ? 'selected' : '' }} value="{{ $value }}">
                            {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
            </div>

                <br>
            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-create>
</x-admin-layout>
