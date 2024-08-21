<x-admin-layout title="Cars">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.car.index') }}">Cars</a></li>
        <li class="active">View</li>
    </x-slot>
    
    <style>
        .nav-tabs {
            display: flex;
            flex-direction: column;
            border-bottom: none;
           
        }

        .nav-item {
            width: 100%;
           
        }

        .nav-link {
            text-align: left;
            border-radius: 0;
            border: 1px solid #ddd;
            margin-bottom: 5px;
            
        }
    </style>

    <x-card title="">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12">
                <div class="card card-border card-primary">

                 <div class="card-header">
                        <div class="m-b-30">
                            <a href="{{ route('admin.car.edit', $car) }}"
                                class="btn btn-primary waves-effect waves-light"><i class="fa fa-pencil"></i> Edit</a>
                                <a href="#"
                                onclick="(function(){if(confirm('Are you sure?')){$('form#delete-form').submit()}})()"
                                class="btn btn-danger btn-custom waves-effect waves-light"><i class="fa fa-trash"></i>
                                Delete</a>
                                <form id="delete-form"
                                action="{{ route('admin.car.destroy', $car) }}"
                                method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-horizontal">

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Brand Name</label>
                                <div class="col-sm-8">
                                    {{ $car->brand->name }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Model Name</label>
                                <div class="col-sm-8">
                                    {{ $car->model_name }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Image</label>
                                <div class="col-sm-8">
                                    <img src="{{ file_asset('files-car', $car->image) }}" 
                                        alt='brand-img' class='img-thumbnail' width='100' height='150'>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Ex-Showroom Price</label>
                                <div class="col-sm-8">
                                    KWD {{ $car->ex_showroom_price }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">On Road Price</label>
                                <div class="col-sm-8">
                                    KWD {{ $car->on_road_price }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Finance Available</label>
                                <div class="col-sm-8">
                                    KWD {{ $car->finance_available }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Insurance</label>
                                <div class="col-sm-8">
                                    KWD {{ $car->insurance }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Service Charge</label>
                                <div class="col-sm-8">
                                    KWD {{ $car->service_charge }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Safety ratings</label>
                                <div class="col-sm-8">
                                    {{ $car->safety_ratings}}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Status</label>
                                <div class="col-sm-8">
                                    {{ config('params.car.status')[$car->status] }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Created At</label>
                                <div class="col-sm-8">
                                    {{ dateTimeFormat($car->created_at) }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Updated At</label>
                                <div class="col-sm-8">
                                    {{ dateTimeFormat($car->updated_at) }}
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </x-card>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-border card-primary">
                <div class="card-header"> 
                    <div class="m-b-30">
                        <h5>Key Features</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label">Air Condition</label>
                            <div class="col-sm-8">
                                {{ ($car->air_condition == 1) ? 'YES' : 'NO' }}
                            </div>
                        </div>
                    </div>
                    <div class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label">Length</label>
                            <div class="col-sm-8">
                                {{ $car->length }} mm
                            </div>
                        </div>
                    </div>
                    <div class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label">Width</label>
                            <div class="col-sm-8">
                                {{ $car->width }} mm
                            </div>
                        </div>
                    </div>
                    <div class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label">Height</label>
                            <div class="col-sm-8">
                                {{ $car->height }} mm
                            </div>
                        </div>
                    </div>
                    <div class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label">Boot Space</label>
                            <div class="col-sm-8">
                                {{ $car->boot_space }} L
                            </div>
                        </div>
                    </div>
                    <div class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label">Power Windows</label>
                            <div class="col-sm-8">
                                {{ $car->power_windows }} 
                            </div>
                        </div>
                    </div>
                    <div class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label">Fuel Tank Capacity</label>
                            <div class="col-sm-8">
                                {{ $car->fuel_tank_capacity }} L
                            </div>
                        </div>
                    </div>
                    <div class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label">Seat Upholstery</label>
                            <div class="col-sm-8">
                                {{ $car->seat_upholstery }} 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-border card-primary">
                <div class="card-header"> 
                    <div class="m-b-30">
                        <h5>Key Specifications</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label">Fuel Types</label>
                            <div class="col-sm-8">
                               {{ $fuelTypes}}
                            </div>
                        </div>
              
                        <div class="form-group row">
                            <label class="col-sm-4 control-label">Transmission Types</label>
                            <div class="col-sm-8">
                                {{ $transmissionTypes}}
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <label class="col-sm-4 control-label">Engine Capacity</label>
                            <div class="col-sm-8">
                                {{ $car->engine_capacity}} cc
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label">Power & Torque</label>
                            <div class="col-sm-8">
                                {{ $car->power}} Bhp  {{ $car->torque}} rpm
                            </div>
                        </div>
                      
                        <div class="form-group row">
                            <label class="col-sm-4 control-label">Drive Train</label>
                            <div class="col-sm-8">
                                {{ $car->drive_train}} 
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label">Acceleration</label>
                            <div class="col-sm-8">
                                {{ $car->acceleration}} sec
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label">Top Speed</label>
                            <div class="col-sm-8">
                                {{ $car->top_speed}} kmph
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label">Avg. Mileage</label>
                            <div class="col-sm-8">
                                {{ $car->mileage}} klmp
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-border card-primary">
    <div class="card-header"> 
        <div class="m-b-30">
            <h5>Other Specifications</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <ul class="nav flex-column nav-tabs tabs" role="tablist" id="business-user-profile-tab" >
                    <li class="nav-item tab">
                        <a class="nav-link active" id="contact-tab-2" data-toggle="tab" href="#contact-2" role="tab" 
                            onclick="onTab('contact')" aria-controls="contact-2" aria-selected="true">
                            <span class="d-block d-sm-none"><i class="fa fa-user"></i></span>
                            <span class="d-none d-sm-block">Engine and Transmission</span>
                        </a>
                    </li>
                    <li class="nav-item tab" >
                        <a class="nav-link" id="images-tab-2" data-toggle="tab" href="#images-2" role="tab" 
                            onclick="onTab('images')" aria-controls="images-2" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-envelope-o"></i></span>
                            <span class="d-none d-sm-block">Fuel & Performance</span>
                        </a>
                    </li>
                    <li class="nav-item tab" >
                        <a class="nav-link" id="colors-tab-2" data-toggle="tab" href="#colors-2" role="tab" 
                            onclick="onTab('colors')" aria-controls="colors-2" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-envelope-o"></i></span>
                            <span class="d-none d-sm-block">Suspension, Steering & Brakes</span>
                        </a>
                    </li>
                    <li class="nav-item tab">
                        <a class="nav-link" id="product_service-tab-2" data-toggle="tab" href="#product_service-2" role="tab" 
                            onclick="onTab('product')" aria-controls="product_service-2" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-cog"></i></span>
                            <span class="d-none d-sm-block">Dimensions & Capacity</span>
                        </a>
                    </li>
                    <li class="nav-item tab">
                        <a class="nav-link" id="comfort-tab-2" data-toggle="tab" href="#comfort-2" role="tab" 
                            onclick="onTab('comfort')" aria-controls="comfort-2" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-cog"></i></span>
                            <span class="d-none d-sm-block">Comfort & Convenience</span>
                        </a>
                    </li>
                    <li class="nav-item tab">
                        <a class="nav-link" id="interior-tab-2" data-toggle="tab" href="#interior-2" role="tab" 
                            onclick="onTab('interior')" aria-controls="interior-2" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-cog"></i></span>
                            <span class="d-none d-sm-block">Interior</span>
                        </a>
                    </li>
                    <li class="nav-item tab">
                        <a class="nav-link" id="exterior-tab-2" data-toggle="tab" href="#exterior-2" role="tab" 
                            onclick="onTab('exterior')" aria-controls="exterior-2" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-cog"></i></span>
                            <span class="d-none d-sm-block">Exterior</span>
                        </a>
                    </li>
                    <li class="nav-item tab">
                        <a class="nav-link" id="exterior-tab-2" data-toggle="tab" href="#exterior-2" role="tab" 
                            onclick="onTab('exterior')" aria-controls="exterior-2" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-cog"></i></span>
                            <span class="d-none d-sm-block">Exterior</span>
                        </a>
                    </li>
                    <li class="nav-item tab">
                        <a class="nav-link" id="safety-tab-2" data-toggle="tab" href="#safety-2" role="tab" 
                            onclick="onTab('safety')" aria-controls="safety-2" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-cog"></i></span>
                            <span class="d-none d-sm-block">Safety</span>
                        </a>
                    </li>
                    <li class="nav-item tab">
                        <a class="nav-link" id="communication-tab-2" data-toggle="tab" href="#communication-2" role="tab" 
                            onclick="onTab('communication')" aria-controls="communication-2" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-cog"></i></span>
                            <span class="d-none d-sm-block">Entertainment & Communication</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-md-9">
                <div class="tab-content">
                    <div class="tab-pane active" id="contact-2" role="tabpanel" aria-labelledby="contact-tab-2">
                        @include('admin.car.show_engine_types')
                    </div>
                    <div class="tab-pane" id="images-2" role="tabpanel" aria-labelledby="images-tab-2">
                        @include('admin.car.show_fuels')
                    </div>
                    <div class="tab-pane" id="colors-2" role="tabpanel" aria-labelledby="colors-tab-2">
                        @include('admin.car.show_suspension')
                    </div>
                    <div class="tab-pane" id="product_service-2" role="tabpanel" aria-labelledby="product_service-tab-2">
                        @include('admin.car.show_diemention')
                    </div>
                    <div class="tab-pane" id="comfort-2" role="tabpanel" aria-labelledby="comfort-tab-2">
                        @include('admin.car.show_interior')
                    </div>
                    <div class="tab-pane" id="interior-2" role="tabpanel" aria-labelledby="interior-tab-2">
                        @include('admin.car.show_exterior')
                    </div>
                    <div class="tab-pane" id="exterior-2" role="tabpanel" aria-labelledby="exterior-tab-2">
                        @include('admin.car.show_safety')
                    </div>
                    <div class="tab-pane" id="safety-2" role="tabpanel" aria-labelledby="safety-tab-2">
                        @include('admin.car.show_comfort')
                    </div>
                    <div class="tab-pane" id="communication-2" role="tabpanel" aria-labelledby="communication-tab-2">
                        @include('admin.car.show_communication')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>








                </div>
                <div class="tab-pane" id="images-2" role="tabpanel" aria-labelledby="images-tab-2">
                    
                </div>
                <div class="tab-pane" id="colors-2" role="tabpanel" aria-labelledby="colors-tab-2">
                    
                </div>
                <div class="tab-pane" id="product_service-2" role="tabpanel" aria-labelledby="product_service-tab-2">
                   
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>

