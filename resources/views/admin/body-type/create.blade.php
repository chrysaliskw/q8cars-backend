<x-admin-layout title="Body Types">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.body-type.index') }}">Body Type</a></li>
        <li class="active">Create</li>
    </x-slot>

   <x-crud-create title="Body Type">
        <x-form method="POST" action="{{ route('admin.body-type.store') }}" class="form" enctype="multipart/form-data">
            <div class="row">

                <div class="col-md-4">
                    <x-form-input type="text" field="name" field-name="Name" value="{{ old('name') }}">
                    </x-form-input>
                </div>

                <div class="col-md-4">
                    <x-form-input type="file" field="icon" field-name="Icon" value="{{ old('icon') }}">
                    </x-form-input>
                    <span class="text-muted">
                        {{'Max size : 2MB,Resolution:45px X 17px'}} 
                    </span>
                </div>
              
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach (config('params.body-type.status') as $value => $label)
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
