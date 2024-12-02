<x-admin-layout title="Sub Admins">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.sub-admin.admin.index') }}">Sub Admins</a></li>
        <li>{{ $admin->name }}</li>
    </x-slot>
    <x-card title="Sub Admin">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-12">
            <div class="card card-border card-primary">
                <div class="card-header">
                    <div class="m-b-30">
                    <a href="{{ route('admin.sub-admin.admin.edit', $admin) }}"
                                class="btn btn-primary waves-effect waves-light"><i class="fa fa-pencil"></i> Edit</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-horizontal">

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Name</label>
                                <div class="col-sm-8">
                                    {{ $admin->name }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Email</label>
                                <div class="col-sm-8">
                                    {{ $admin->email }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Profile picture</label>
                                <div class="col-sm-4">
                                @if($admin->picture)
                                    <img src="{{ file_asset('files-admin', $admin->picture) }}"
                                         alt="profile-img" class="img-thumbnail" width="100" height="150" />
                                @else
                                    <img src="{{ asset('moltran-asset/images/dp.png') }}"
                                         alt="profile-img" class="img-thumbnail" width="100" height="150" />
                                @endif
                                </div>
                            </div>


                            
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Role</label>
                                <div class="col-sm-8">

                                    <a href="{{ route('admin.sub-admin.role.show', $role->id) }}">{{ $role->name }}</a>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Status</label>
                                <div class="col-sm-8">

                                    {{ config('params.admin.status')[$admin->status]; }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Created At</label>
                                <div class="col-sm-8">
                                    {{ dateTimeFormat($admin->created_at) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Updated At</label>
                                <div class="col-sm-8">
                                    {{ dateTimeFormat($admin->updated_at) }}
                                </div>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-card>

</x-admin-layout>

