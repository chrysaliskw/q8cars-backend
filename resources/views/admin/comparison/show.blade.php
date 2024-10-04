<x-admin-layout title="Car Comparison">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.comparison.index') }}">Car Comparison</a></li>
        <li class="active">View</li>
    </x-slot>
    
    <x-card title="">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12">
                <div class="card card-border card-primary">
                    <div class="card-header">
                        <div class="m-b-30">
                            <a href="{{ route('admin.comparison.edit', $id) }}"
                            class="btn btn-primary waves-effect waves-light"><i class="fa fa-pencil"></i> Edit</a>
                            <a href="#"
                                onclick="(function(){if(confirm('Are you sure?')){$('form#delete-form').submit()}})()"
                                class="btn btn-danger btn-custom waves-effect waves-light"><i class="fa fa-trash"></i>
                                Delete</a>
                                <form id="delete-form"
                                action="{{ route('admin.comparison.destroy', $id) }}"
                                method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class="table table-bordered table-striped table-hover">
                                <thead style="color: #bc1d1d; font-weight: bold;">
                                    <tr>
                                        <th>MAIN CAR</th>
                                        <th>CAR 1</th>
                                        <th>CAR 2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding: 15px;">
                                            <strong style="color: #bc1d1d; font-weight: bold;">Main Brand</strong>: <span style="padding-left: 10px;">{!! $viewData['Main Brand'] !!}</span><br>
                                            <strong style="color: #bc1d1d; font-weight: bold;">Main Car</strong>: <span style="padding-left: 10px;">{!! $viewData['Car Model'] !!}</span>
                                        </td>
                                        <td style="padding: 15px;">
                                            <strong style="color: #bc1d1d; font-weight: bold;">Brand 1</strong>: <span style="padding-left: 10px;">{!! $viewData['Brand 1'] !!}</span><br>
                                            <strong style="color: #bc1d1d; font-weight: bold;">Car 1 Model</strong>: <span style="padding-left: 10px;">{!! $viewData['Car 1 Model'] !!}</span><br>
                                            <strong style="color: #bc1d1d; font-weight: bold;">Car version 1</strong>: <span style="padding-left: 10px;">{!! $viewData['Car version 1'] !!}</span>
                                        </td>
                                        <td style="padding: 15px;">
                                            <strong style="color: #bc1d1d; font-weight: bold;">Brand 2</strong>: <span style="padding-left: 10px;">{!! $viewData['Brand 2'] !!}</span><br>
                                            <strong style="color: #bc1d1d; font-weight: bold;">Car 2 Model</strong>: <span style="padding-left: 10px;">{!! $viewData['Car 2 Model'] !!}</span><br>
                                            <strong style="color: #bc1d1d; font-weight: bold;">Car version 2</strong>: <span style="padding-left: 10px;">{!! $viewData['Car version 2'] !!}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                        

                            

                        </div>
                    </div>
                </div>
            </div>
    </x-card>
</x-admin-layout>

