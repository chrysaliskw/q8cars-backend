<x-admin-layout title="Users">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.user.index') }}">Users</a></li>
        <li class="active">View</li>
    </x-slot>
    
   <x-card title="">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12">
                <div class="card card-border card-primary">

                 <div class="card-header">
                        <div class="m-b-30">
                            <a href="{{ route('admin.user.edit', $user) }}"
                                class="btn btn-primary waves-effect waves-light"><i class="fa fa-pencil"></i> Edit</a>
                                <a href="#"
                                onclick="(function(){if(confirm('Are you sure?')){$('form#delete-form').submit()}})()"
                                class="btn btn-danger btn-custom waves-effect waves-light"><i class="fa fa-trash"></i>
                                Delete</a>
                                <form id="delete-form"
                                action="{{ route('admin.user.destroy', $user) }}"
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
                                    {{ $user->name }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Email</label>
                                <div class="col-sm-8">
                                    {{ $user->email }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Picture</label>
                                <div class="col-sm-8">
                                    <img src="{{ file_asset('files-user', $user->picture) }}" 
                                        alt='user-img' class='img-thumbnail' width='100' height='150'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Mobile</label>
                                <div class="col-sm-8">
                                    {{ $user->phone_code.$user->mobile }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Address</label>
                                <div class="col-sm-8">
                                    {{ $user->address }}
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Status</label>
                                <div class="col-sm-8">
                                    {{ config('params.user.status')[$user->status] }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Created At</label>
                                <div class="col-sm-8">
                                    {{ dateTimeFormat($user->created_at) }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Updated At</label>
                                <div class="col-sm-8">
                                    {{ dateTimeFormat($user->updated_at) }}
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </x-card>
    
</x-admin-layout>

