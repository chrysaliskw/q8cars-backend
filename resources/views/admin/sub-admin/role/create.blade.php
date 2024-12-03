<x-admin-layout title="Roles">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.sub-admin.role.index') }}">Roles</a></li>
        <li class="active">Create</li>
    </x-slot>
    <x-crud-create title="Roles">
    <x-form method="POST" action="{{ route('admin.sub-admin.role.store') }}" class="form" >
            <div class="row">
                <div class="col-md-3">
                    <x-form-input type="text" field="name" field-name="Name" value="{{ old('name') }}">
                    </x-form-input>
                </div>
            </div>
            <div clss="row">

                <div class="all-permissions d-flex align-items-center justify-content-between pb-4">
                <label class="control-label" for="permissions">Permissions</label>
                <div class="form-check form-check-inline col-md-2">
                            <input class="form-check-input" type="checkbox" id="selectAll" >
                                    &nbsp;<label class="form-check-label" for="selectAll" style="color: black;">
                                       Select all
                            </label>

                        </div>
                </div>
                <div class="row">
                    @foreach($permissions as $permission)
                        @php

                        @endphp
                        <div class="form-check form-check-inline col-md-2">
                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                    id="permission_{{ $permission->id }}" value="{{ $permission->id }}" >
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
    </x-crud-create>
    <x-slot name="scripts">

    <script>
        $("#selectAll").click(function() {
            $("input[type=checkbox]").prop("checked", $(this).prop("checked"));
        });
    </script>

    </x-slot>
</x-admin-layout>
