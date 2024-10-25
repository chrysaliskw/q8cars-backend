<x-admin-layout title="Notifications">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.notifications.index') }}">Notifications</a></li>
        <li class="active">View</li>
    </x-slot>

    <x-card title="">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12">
                <div class="card card-border card-primary">
                    <div class="card-header">
                        <div class="m-b-30">
                            <a href="{{ route('admin.notifications.edit', $notification) }}"
                            class="btn btn-primary waves-effect waves-light"><i class="fa fa-pencil"></i> Edit</a>
                            <a href="#"
                                onclick="(function(){if(confirm('Are you sure?')){$('form#delete-form').submit()}})()"
                                class="btn btn-danger btn-custom waves-effect waves-light"><i class="fa fa-trash"></i>
                                Delete</a>
                                <form id="delete-form"
                                action="{{ route('admin.notifications.destroy', $notification) }}"
                                method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-horizontal">
                            @foreach($viewData as $key => $value)
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">{{ $key }}</label>
                                    <div class="col-sm-8">

                                        {!! $value !!}

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
    </x-card>
</x-admin-layout>

