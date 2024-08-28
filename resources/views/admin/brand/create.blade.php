<x-admin-layout title="Brands">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.brand.index') }}">Brands</a></li>
        <li class="active">Create</li>
    </x-slot>

   <x-crud-create title="Brands">
        <x-form method="POST" action="{{ route('admin.brand.store') }}" class="form" enctype="multipart/form-data">
            <div class="row">

                <div class="col-md-4">
                    <x-form-input type="text" field="name" field-name="Name" value="{{ old('name') }}">
                    </x-form-input>
                </div>

                <div class="col-md-4">
                    <x-form-input type="file" field="icon" field-name="Icon" value="{{ old('icon') }}">
                    </x-form-input>
                    <span class="text-muted">
                        {{'Max size : 2MB'}} 
                    </span>
                </div>
                <div class="col-md-4">
                    <x-form-select field="is_top_brand" field-name="Is Top Brand ?" defaultPrompt="Select">
                        @foreach (config('params.brand.is_top_brand') as $value => $label)
                            <option {{ old('status') == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
                
            </div> 

               <div class="row">
               {{-- <div class="col-md-4">
                    <x-form-select field="is_popular" field-name="Is Popular ?" defaultPrompt="Select">
                        @foreach (config('params.brand.is_top_brand') as $value => $label)
                            <option {{ old('is_popular') == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>--}}

                <div class="col-md-4">
                    <x-form-select field="is_recent_purchased" field-name="Is Recent Purchased ?" defaultPrompt="Select">
                        @foreach (config('params.brand.is_top_brand') as $value => $label)
                            <option {{ old('is_recent_purchased') == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
              
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach (config('params.brand.status') as $value => $label)
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
