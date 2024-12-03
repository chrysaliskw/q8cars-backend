<x-admin-layout title="Roles">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.sub-admin.role.index') }}">Roles</a></li>
        <li class="active">Update</li>
    </x-slot>



    <x-crud-update title="Role" >
        <x-form method="PUT" action="{{ route('admin.sub-admin.role.update', $role->id) }}" class="form">
            <div class="row">
                <div class="col-md-3">
                    <x-form-input type="text" field="name" field-name="Name"
                        value="{{ $role->name }}">
                    </x-form-input>
                </div>
            </div>
            <div clss="row">

                <label class="control-label" for="permissions">Permissions</label>
                <div class="row">
                    @foreach($permissions as $permission)
                        @php
                            $attrCheck = in_array($permission->id, $currentPermissions) ? 'checked' : '';
                        @endphp
                        <div class="form-check form-check-inline col-md-2">
                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                    id="permission_{{ $permission->id }}" value="{{ $permission->id }}" {{ $attrCheck }}>
                                    &nbsp;<label class="form-check-label" for="permission_{{ $permission->id }}" style="color: black;">
                                        {{ $permission->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <span class="error" role="alert">
                        @error('permissions')
                            {{ $message }}</br>
                        @enderror
                </span>

            </div><br>

            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-update>

</x-admin-layout>
