<x-admin-layout title="Banners">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.banner.index') }}">Banners</a></li>
        <li class="active">Create</li>
    </x-slot>

   <x-crud-create title="Banner" >
        <x-form method="POST" action="{{ route('admin.banner.store') }}" class="form" enctype="multipart/form-data">
            <div class="row">

                <div class="col-md-4">
                    <x-form-input type="text" field="name" field-name="Name" value="{{ old('name') }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-select field="page" field-name="Page" defaultPrompt="Select page">
                        @foreach (config('params.banner.page') as $value => $label)
                            <option {{ old('page') == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>

                <div class="col-md-4">
                    <x-form-input type="file" field="file_name" field-name="Image" value="{{ old('file_name') }}">
                    </x-form-input>
                    <span class="text-muted">
                        {{'Max size : 2MB, Dimensions: 1400x700 px'}} 
                    </span>
                </div>
               
                
            </div> 

               <div class="row">

                <div class="col-md-4">
                    <x-form-input type="file" field="file_name_mobile_view" field-name="Mobile View Image" value="{{ old('file_name_mobile_view') }}">
                    </x-form-input>
                    <span class="text-muted">
                        {{'Max size : 2MB, Dimensions: 375x385 px'}} 
                    </span>
                </div>
              
                <div class="col-md-4">
                    <x-form-input type="text" field="sort_order" field-name="Sort Order" value="{{ old('sort_order') }}">
                    </x-form-input>
                </div>
             
              
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach (config('params.banner.status') as $value => $label)
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
