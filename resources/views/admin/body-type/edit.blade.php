<x-admin-layout title="Body Types">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.body-type.index') }}">Body Types</a></li>
        <li class="active">Update</li>
    </x-slot>

    <x-crud-update title="Body Type">
        <x-form method="PUT" 
            action="{{ route('admin.body-type.update', $bodyType) }}" 
            class="form" enctype="multipart/form-data">
            <div class="row">

                <div class="col-md-3">
                    <x-form-input type="text" field="name" field-name="Name" value="{{ $bodyType->name  ?? old('name')}}"></x-form-input>
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
                        <img src="{{ file_asset('files-body_type', $bodyType->icon) }}" 
                            alt="body-type-img" class="img-thumbnail" width="100" height="150">
                            <span class="error" role="alert">
                            @error('icon')
                                {{ $message }}</br>
                            @enderror
                        </span>  
                       
                    </div>
                </div>
              
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach (config('params.body-type.status') as $value => $label)
                            <option {{ old('status',$bodyType->status) == $value ? 'Selected' : '' }} value="{{ $value }}">
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