<x-admin-layout title="Trashed Body Types">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.trash-body-type.index') }}">Trashed Body Types</a></li>
        <li class="active">View</li>
    </x-slot>
    <x-card title="">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12">
                <div class="card card-border card-primary">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <div class="form-horizontal">

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Name</label>
                                <div class="col-sm-8">
                                    {{ $bodyType->name }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Icon</label>
                                <div class="col-sm-8">
                                    <img src="{{ file_asset('files-body_type', $bodyType->icon) }}" 
                                        alt='body-type-img' class='img-thumbnail' width='100' height='150'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Status</label>
                                <div class="col-sm-8">
                                    {{ config('params.body-type.status')[$bodyType->status] }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Created At</label>
                                <div class="col-sm-8">
                                    {{ dateTimeFormat($bodyType->created_at) }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Updated At</label>
                                <div class="col-sm-8">
                                    {{ dateTimeFormat($bodyType->updated_at) }}
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </x-card>
</x-admin-layout>

