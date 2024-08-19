<x-admin-layout title="Cars">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.car.index') }}">Brands</a></li>
        <li class="active">Update</li>
    </x-slot>

    <x-crud-update title="Car">
        <x-form method="PUT" 
            action="{{ route('admin.car.update', $car) }}" 
            class="form" enctype="multipart/form-data">
            <div class="row">

                <div class="col-md-3">
                    <x-form-input type="text" field="model_name" field-name="Model Name" value="{{ $car->model_name  ?? old('model_name')}}"></x-form-input>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="image" class="control-label">Image</label><br>
                        <img src="{{ file_asset('files-car', $car->image) }}" 
                            alt="image-img" class="img-thumbnail" width="100" height="150">
                        <input id="image" type="file" name="image" class="form-control">
                        <span class="error" role="alert">
                            @error('image')
                                {{ $message }}</br>
                            @enderror
                        </span>
                        <span class="text-muted">
                            {{'Max size : 2MB'}} 
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select">
                        @foreach (config('params.car.status') as $value => $label)
                            <option {{ old('status', $car->status) == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
                
            </div> 

               <div class="row">
              
               

            </div>

            <div class="col-md-10">
                <x-form-submit>Save</x-form-submit>
            </div>
        </x-form>
    </x-crud-update>
    
    <x-slot name="scripts">
       
    </x-slot>
    
</x-admin-layout>