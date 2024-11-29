<x-admin-layout title="Roles">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.sub-admin.role.index') }}">Roles</a></li>
        <li class="active">View</li>
    </x-slot>
    <x-card title="Sub Admin">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12">
                <div class="card card-border card-primary">
                    <div class="card-header">
                        <div class="m-b-30">
                            <a href="{{ route('admin.sub-admin.role.edit', $role) }}"
                                    class="btn btn-primary waves-effect waves-light"><i class="fa fa-pencil"></i> Edit</a>
                            <a href="#"
                                onclick="(function(){if(confirm('Are you sure?')){$('form#delete-form').submit()}})()"
                                class="btn btn-danger btn-custom waves-effect waves-light"><i class="fa fa-trash"></i>
                                Delete</a>
                            <form id="delete-form"
                                action="{{ route('admin.sub-admin.role.destroy', $role) }}"
                                method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">Name</label>
                                    <div class="col-sm-8">
                                        {{ $role->name }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">Permissions</label>
                                    <div class="col-sm-8">
                                        @foreach($role->permissions as $permission)
                                            <a href="{{ route('admin.sub-admin.permission.show', $permission) }}">
                                                {{ $permission->name}}
                                            </a> <br>
                                        @endforeach
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">Created At</label>
                                    <div class="col-sm-8">
                                        {{ dateTimeFormat($role->created_at) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">Updated At</label>
                                    <div class="col-sm-8">
                                        {{ dateTimeFormat($role->updated_at) }}
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-card>
</x-admin-layout>

