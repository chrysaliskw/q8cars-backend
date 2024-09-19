<x-admin-layout title="Colors">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.color.index') }}">Colors</a></li>
        <li class="active">Create</li>
    </x-slot>

   <x-crud-create title="Brands">
        <x-form method="POST" action="{{ route('admin.color.store') }}" class="form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4">
                    <x-form-input type="text" field="name" field-name="Name" value="{{ old('name') }}">
                    </x-form-input>
                </div>

                <div class="col-md-4"> 
                    <x-form-multi-select field="brand" field-name="Brand" defaultPrompt="Select Brand" id="userSelect">
                        @foreach($brandArr as $key => $value)
                            <option {{ old("brand") == $key ? "selected" : "" }} value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </x-form--multi-select>
                </div>              
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach (config('params.brand_color.status') as $value => $label)
                            <option {{ old('status') == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
            </div>
            <div class="col-md-10">
                <x-form-submit>Save</x-form-submit>
            </div>
        </x-form>
    </x-crud-create>
</x-admin-layout>
