<x-admin-layout title="Brands">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.brand.index') }}">Brands</a></li>
        <li class="active">View</li>
    </x-slot>
    
   <x-card title="">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12">
                <div class="card card-border card-primary">

                 <div class="card-header">
                        <div class="m-b-30">
                            <a href="{{ route('admin.brand.edit', $brand) }}"
                                class="btn btn-primary waves-effect waves-light"><i class="fa fa-pencil"></i> Edit</a>
                                <a href="#"
                                onclick="(function(){if(confirm('Are you sure?')){$('form#delete-form').submit()}})()"
                                class="btn btn-danger btn-custom waves-effect waves-light"><i class="fa fa-trash"></i>
                                Delete</a>
                                <form id="delete-form"
                                action="{{ route('admin.brand.destroy', $brand) }}"
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
                                    {{ $brand->name }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Icon</label>
                                <div class="col-sm-8">
                                    <img src="{{ file_asset('files-brand', $brand->icon) }}" 
                                        alt='brand-img' class='img-thumbnail' width='100' height='150'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Is Top Brand?</label>
                                <div class="col-sm-8">
                                {{ config('params.brand.is_top_brand')[$brand->is_top_brand] }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Status</label>
                                <div class="col-sm-8">
                                    {{ config('params.brand.status')[$brand->status] }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Created At</label>
                                <div class="col-sm-8">
                                    {{ dateTimeFormat($brand->created_at) }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Updated At</label>
                                <div class="col-sm-8">
                                    {{ dateTimeFormat($brand->updated_at) }}
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </x-card>
    
</x-admin-layout>

