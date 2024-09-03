<x-admin-layout title="Brands">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.brand.index') }}">Brands</a></li>
        <li class="active">Update</li>
    </x-slot>

    <x-crud-update title="Brand">
        <x-form method="PUT" 
            action="{{ route('admin.brand.update', $brand) }}" 
            class="form" enctype="multipart/form-data">
            <div class="row">

                <div class="col-md-3">
                    <x-form-input type="text" field="name" field-name="Name" value="{{ $brand->name  ?? old('name')}}"></x-form-input>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="icon" class="control-label">Icon</label>
                        <div>
                            <input id="icon" type="file" name="icon" class="form-control" style="padding-left: 2px;padding-top:2px">
                       
                            <span class="text-muted">
                            {{'Max size : 2MB'}} 
                        </span>
                            </div>
                        <img src="{{ file_asset('files-brand', $brand->icon) }}" 
                            alt="brand-img" class="img-thumbnail" width="100" height="150">
                            <span class="error" role="alert">
                            @error('icon')
                                {{ $message }}</br>
                            @enderror
                        </span>  
                       
                    </div>
                </div>
                <div class="col-md-4">
                    <x-form-select field="is_top_brand" field-name="Is Top Brand ?" defaultPrompt="Select">
                        @foreach (config('params.brand.is_top_brand') as $value => $label)
                            <option {{ old('is_top_brand', $brand->is_top_brand) == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
                
            </div> 

               <div class="row">
             {{--  <div class="col-md-4">
                    <x-form-select field="is_popular" field-name="Is Popular ?" defaultPrompt="Select">
                        @foreach (config('params.brand.is_top_brand') as $value => $label)
                            <option {{ old('is_popular') == $brand->is_popular ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
                --}}
                <div class="col-md-4">
                    <x-form-select field="is_recently_purchased" field-name="Is Recent Purchased ?" defaultPrompt="Select">
                        @foreach (config('params.brand.is_top_brand') as $value => $label)
                            <option {{ old('is_recently_purchased', $brand->is_top_brand) == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach (config('params.brand.status') as $value => $label)
                            <option {{ old('status',$brand->status) == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>

            </div>

            <div class="col-md-10">
                <x-form-submit>Save</x-form-submit>
            </div>
        </x-form>
    </x-crud-update>
    
    <x-slot name="scripts">
       
    </x-slot>
    
</x-admin-layout>