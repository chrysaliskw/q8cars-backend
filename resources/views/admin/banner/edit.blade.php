<x-admin-layout title="Banners">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.banner.index') }}">Banners</a></li>
        <li class="active">Update</li>
    </x-slot>

    <x-crud-update title="Banner">
        <x-form method="PUT" 
            action="{{ route('admin.banner.update', $banner) }}" 
            class="form" enctype="multipart/form-data">
            <div class="row">

                <div class="col-md-4">
                    <x-form-input type="text" field="name" field-name="Name" value="{{ $banner->name  ?? old('name')}}"></x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-select field="page" field-name="Page" defaultPrompt="Select page">
                        @foreach (config('params.banner.page') as $value => $label)
                            <option {{ old('page',$banner->page) == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="icon" class="control-label">Image</label>
                        <div>
                            <input id="icon" type="file" name="file_name" class="form-control" style="padding-left: 2px;padding-top:2px">
                       
                            <span class="text-muted">
                            {{'Max size : 2MB,  Dimensions: 1400x700 px'}} 
                        </span>
                            </div>
                        <img src="{{ file_asset('files-banner', $banner->file_name) }}" 
                            alt="banner-img" class="img-thumbnail" width="100" height="150">
                            <span class="error" role="alert">
                            @error('file_name')
                                {{ $message }}</br>
                            @enderror
                        </span>  
                       
                    </div>
                </div>  
            </div> 

            <div class="row">

             <div class="col-md-4">
                    <div class="form-group">
                        <label for="icon" class="control-label">Mobile View Image</label>
                        <div>
                            <input id="icon" type="file" name="file_name_mobile_view" class="form-control" style="padding-left: 2px;padding-top:2px">
                       
                            <span class="text-muted">
                            {{'Max size : 2MB,  Dimensions: 375x385 px'}} 
                        </span>
                            </div>
                        <img src="{{ file_asset('files-banner', $banner->file_name_mobile_view) }}" 
                            alt="banner-img" class="img-thumbnail" width="100" height="150">
                            <span class="error" role="alert">
                            @error('file_name')
                                {{ $message }}</br>
                            @enderror
                        </span>  
                       
                    </div>
                </div>  
           
                <div class="col-md-4">
                    <x-form-input type="text" field="sort_order" field-name="Sort Order" value="{{ old('sort_order',$banner->sort_order) }}">
                    </x-form-input>
                </div>  
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach (config('params.banner.status') as $value => $label)
                            <option {{ old('status',$banner->status) == $value ? 'Selected' : '' }} value="{{ $value }}">
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