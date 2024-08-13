<x-admin-layout title="User">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.user.index') }}">Users</a></li>
        <li class="active">Create</li>
    </x-slot>
    <x-crud-create title="User">
        <x-form method="POST" action="{{ route('admin.user.store') }}" class="form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4">
                    <x-form-input type="text" field="name" field-name="Name" value="{{ old('name') }}"></x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="email" field="email" field-name="Email" value="{{ old('email') }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="file" field="picture" field-name="Picture" value="{{ old('picture') }}">
                    </x-form-input>
                    <span class="text-muted">
                        {{'Max size : 2MB'}} 
                    </span>
                </div>
            </div>  
            <div class="row">
                <div class="col-md-4">
                    <x-form-input readonly type="text" field="phone_code" field-name="Country Code" 
                        value="{{ +965 }}" readonly ></x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="mobile" field-name="Mobile" value="{{ old('mobile') }}"></x-form-input>
                </div>   
                
                <div class="col-md-4">
                    <x-form-textarea field="address" field-name="Address" fieldValue="{{ old('address') }}"></x-form-textarea>
                </div>
            </div>
            <div class="row">
            <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach(config('params.user.status') as $value => $label)
                            <option {{ old("status") == $value ? "Selected" : "" }} value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
            </div>
            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-create>

    <x-slot name="scripts">
        <script type="application/javascript">
        </script>
    </x-slot>
</x-admin-layout>