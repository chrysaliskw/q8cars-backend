<x-admin-layout title="User">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.user.index') }}">Users</a></li>
        <li class="active">Update</li>
    </x-slot>
    <x-crud-update title="User">
        <x-form method="PUT" action="{{ route('admin.user.update', $user->id) }}" class="form">
            <div class="row">
                <div class="col-md-4">
                    <x-form-input type="text" field="name" field-name="Name" value="{{ $user->name }}"></x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="email" field="email" field-name="Email" value="{{ $user->email }}"></x-form-input>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="picture" class="control-label">Picture</label><br>
                        <img src="{{ file_asset('files-user', $user->picture) }}" 
                            alt="user-img" class="img-thumbnail" width="100" height="150">
                        <input id="picture" type="file" name="picture" class="form-control">
                        <span class="error" role="alert">
                            @error('picture')
                                {{ $message }}</br>
                            @enderror
                        </span>
                        <span class="text-muted">
                            {{'Max size : 2MB'}} 
                        </span>
                    </div>
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-4">
                    <x-form-input type="text" field="phone_code" field-name="Country code" value="{{ $user->phone_code }}" id="phone_code"></x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="mobile" field-name="Mobile" value="{{ $user->mobile }}"></x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-textarea field="address" field-name="Address" field-value="{{ $user->address }}"> </x-form-textarea>
                </div>
            </div>
            <div class="row">
            <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach(config('params.user.status') as $value => $label)
                            <option {{ $user->status == $value ? "Selected" : "" }} value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
               
            </div>
            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-update>
    
    <x-slot name="scripts">
        <script type="application/javascript">
            
        </script>
    </x-slot>
</x-admin-layout>