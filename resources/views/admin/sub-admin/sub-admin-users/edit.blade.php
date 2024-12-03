<x-admin-layout title="Sub Admins">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.sub-admin.admin.index') }}">Sub Admins</a></li>
        <li class="active">Update</li>
    </x-slot>
    <x-crud-update title="SubAdmin" >
        <x-form method="PUT" action="{{ route('admin.sub-admin.admin.update', $admin->id) }}" class="form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4">
                    <x-form-input type="text" field="name" field-name="Name" value=" {{ $admin->name}}">
                    </x-form-input>
                </div>

                <div class="col-md-4">
                    <x-form-input type="email" field="email" field-name="Email" value=" {{ $admin->email}}"></x-form-input>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="picture" class="control-label">Image</label><br>
                        <input  id="picture" type="file" name="picture" class="form-control">
                        <span class="error" role="alert">
                            @error('picture')
                                {{ $message }}<br>
                            @enderror
                        </span>
                        @if (isset($admin->picture))
                        <img src="{{ file_asset('files-admin',$admin->picture) }}" alt="profile-img"
                            class="img-thumbnail" width="100" height="150">
                        @endif
                        <!-- <img src="{{ file_asset('files-admin', $admin->picture) }}"
                            alt="profile-img" class="img-thumbnail" width="100" height="150"> -->

                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-4">
                    <x-form-select field="role" field-name="Role" defaultPrompt="Select role">
                        @foreach($roles as $role)
                            @php
                                $id = $role->id;
                            @endphp
                            <option  {{ $roleId == $id ? "Selected" : "" }} value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </x-form-select>
                </div>

                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach(config('params.admin.status') as $value => $label)
                            <option {{ $admin->status == $value ? "Selected" : "" }} value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
                <div class="col-md-4">
                    <label  for="password-text" style="margin-right:100px">Password</label>
                    <div class="input-group" >
                        <input type="text" id="password" name="password" value="" class="form-control" >
                        <div class="input-group-prepend">
                            <span class="input-group-text"><a style="height:1px;margin-top:-20px;cursor:pointer"class="input-group-addon btn-crs" value="{{ $admin->pasword }}" onClick="randomPassword(10);">Generate</a></span>
                        </div>
                    </div>
                    <span class="error" role="alert">
                            @error('password')
                                {{ $message }}<br>
                            @enderror
                    </span>
                </div>


            </div>


            <br>

            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-update>

    <x-slot name="scripts">
        <script type="application/javascript">
            function randomPassword(length) {
                var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOP1234567890";
                var pass = "";
                for (var x = 0; x < length; x++) {
                    var i = Math.floor(Math.random() * chars.length);
                    pass += chars.charAt(i);
                }
                document.getElementById("password").value = pass;
            }


        </script>
    </x-slot>

</x-admin-layout>
