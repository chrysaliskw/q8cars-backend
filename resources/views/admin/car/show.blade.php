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
                                onclick="showDeleteConfirmation(event)"
                                class="btn btn-danger btn-custom waves-effect waves-light"><i class="fa fa-trash"></i>
                                Delete</a>
                            <form id="delete-form"
                                action="{{ route('admin.car.destroy', $car) }}"
                                method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                            <a href="{{ route('admin.car-version.edit', $carVarient) }}"
                                class="btn btn-info waves-effect waves-light float-right" >
                                 + Add Car Version</a>

                                 <a href="{{ route('admin.car.360-view.add', ['id' => $car]) }}"
                                class="btn btn-warning waves-effect waves-light float-right" style="margin-right:10px ;">
                                 + Add 360 view images</a>
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
                                <label class="col-sm-4 control-label">Is Upcoming</label>
                                <div class="col-sm-8">
                                    {{$car->is_upcoming == 1 ? 'Yes' :'No' }}
                                </div>
                            </div>
                            @if($car->is_upcoming == 2)
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Is Just Launched</label>
                                <div class="col-sm-8">
                                    {{$car->is_just_launched == 1 ? 'Yes' :'No' }}
                                </div>
                            </div>
                            @if($car->is_just_launched == 1)
                            <div class="form-group row">
                                <label class="col-sm-4 control-label"> Just Launched Sort Order</label>
                                <div class="col-sm-8">
                                    {{$car->just_launch_sort_order }}
                                </div>
                            </div>
                            @endif
                            @endif


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
                                    @for($i = 1; $i <= $car->safety_ratings ; $i++)
                                        <i class="fa fa-star" style="color: red;"></i>
                                    @endfor
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Colors</label>
                                <div class="col-sm-8">

                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach($colors as $c)
                                        @if($i == count($colors)-1)
                                            {{$c}}
                                        @else
                                            {{$c}},
                                        @endif
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Professions</label>
                                <div class="col-sm-8">
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach($professions as $c)
                                        @if($i == count($professions)-1)
                                            {{$c}}
                                        @else
                                            {{$c}},
                                        @endif
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Travel Types</label>
                                <div class="col-sm-8">
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach($travel_types as $c)
                                        @if($i == count($travel_types)-1)
                                            {{$c}}
                                        @else
                                            {{$c}},
                                        @endif
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
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
                             <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
                            aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm Delete</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure that you want to delete this item?                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-danger"
                                            onclick="submitDeleteForm()">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="deleteVideoConfirmationModal" tabindex="-1" role="dialog"
                            aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm Delete</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure that you want to delete this item?                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-danger"
                                            onclick="submitVideoDeleteForm()">Delete</button>
                                    </div>
                                </div>
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
                @if($carVarient->keyFeature)
                @foreach($carVarient->keyFeature as $spec)
                <div class="form-group row">
                    <label class="col-sm-4 control-label">{{ $spec->specification }}</label>
                    <div class="col-sm-8">
                         @if($spec->input_type == 1)
                         {{ $spec->value}} {{$spec->unit}}
                         @else
                            @if($spec->value == 1)
                                <i class="fa fa-check" style="color:green;"></i>
                            @else
                                <i class="fa fa-times" style="color:red;"></i>
                            @endif
                         @endif
                    </div>
                </div>
                @endforeach
                @endif
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
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Fuel Types</label>
                        <div class="col-sm-6">
                            @php
                                $i = 0;
                            @endphp
                            @foreach($fuelTypes as $c)
                                @if($i == count($fuelTypes)-1)
                                    {{$c}}
                                @else
                                    {{$c}},
                                @endif
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Engine Capacity</label>
                        <div class="col-sm-8">
                            {{ $car->engine_capacity .' cc' }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Power & Torque</label>
                        <div class="col-sm-8">
                            {{ $car->power .' Bhp  -'.$car->torque.' rpm' }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Seat Capacity</label>
                        <div class="col-sm-8">
                            {{ $car->seat_capacity }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Mileage</label>
                        <div class="col-sm-8">
                            {{ $car->mileage.' kmpl' }}
                        </div>
                    </div>
                @if($carVarient->keySpec)
                @foreach($carVarient->keySpec as $spec)
                <div class="form-group row">
                    <label class="col-sm-4 control-label">{{ $spec->specification }}</label>
                    <div class="col-sm-8">
                         @if($spec->input_type == 1)
                         {{ $spec->value}} {{$spec->unit}}
                         @else
                            @if($spec->value == 1)
                                <i class="fa fa-check" style="color:green;"></i>
                            @else
                                <i class="fa fa-times" style="color:red;"></i>
                            @endif
                         @endif
                    </div>
                </div>
                @endforeach
                @endif
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
        <div class="card-body car-spec">
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
                            @include('admin.car.show-section.show_engine_types')
                        </div>
                        <div class="tab-pane" id="images-2" role="tabpanel" aria-labelledby="images-tab-2">
                            @include('admin.car.show-section.show_fuel')
                        </div>
                        <div class="tab-pane" id="colors-2" role="tabpanel" aria-labelledby="colors-tab-2">
                            @include('admin.car.show-section.show_suspension')
                        </div>
                        <div class="tab-pane" id="product_service-2" role="tabpanel" aria-labelledby="product_service-tab-2">
                            @include('admin.car.show-section.show_dimention')
                        </div>
                        <div class="tab-pane" id="comfort-2" role="tabpanel" aria-labelledby="comfort-tab-2">
                            @include('admin.car.show-section.show_comfort')
                        </div>
                        <div class="tab-pane" id="interior-2" role="tabpanel" aria-labelledby="interior-tab-2">
                            @include('admin.car.show-section.show_interior')
                        </div>
                        <div class="tab-pane" id="exterior-2" role="tabpanel" aria-labelledby="exterior-tab-2">
                            @include('admin.car.show-section.show_exterior')
                        </div>
                        <div class="tab-pane" id="safety-2" role="tabpanel" aria-labelledby="safety-tab-2">
                            @include('admin.car.show-section.show_safety')
                        </div>
                        <div class="tab-pane" id="communication-2" role="tabpanel" aria-labelledby="communication-tab-2">
                            @include('admin.car.show-section.show_communication')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-border card-primary">
        <div class="card-header">
            <div class="m-b-30">
                <h5>Additional Images</h5>
            </div>
        </div>
        <div class="card-body car-spec car-add-image">
            <div class="row">
                <div class="col-md-3">
                    <ul class="nav flex-column nav-tabs tabs" role="tablist" id="business-user-profile-tab" >
                        <li class="nav-item tab">
                            <a class="nav-link active" id="section0-tab-2" data-toggle="tab" href="#section0-2" role="tab"
                                onclick="onTab('section0')" aria-controls="section0-2" aria-selected="true">
                                <span class="d-block d-sm-none"><i class="fa fa-user"></i></span>
                                <span class="d-none d-sm-block">Main Images</span>
                            </a>
                        </li>
                        <li class="nav-item tab">
                            <a class="nav-link active" id="section1-tab-2" data-toggle="tab" href="#section1-2" role="tab"
                                onclick="onTab('section1')" aria-controls="section1-2" aria-selected="true">
                                <span class="d-block d-sm-none"><i class="fa fa-user"></i></span>
                                <span class="d-none d-sm-block">Exterior</span>
                            </a>
                        </li>
                        <li class="nav-item tab" >
                            <a class="nav-link" id="section2-tab-2" data-toggle="tab" href="#section2-2" role="tab"
                                onclick="onTab('section2')" aria-controls="section2-2" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-envelope-o"></i></span>
                                <span class="d-none d-sm-block">Interior</span>
                            </a>
                        </li>
                        <li class="nav-item tab" >
                            <a class="nav-link" id="section3-tab-2" data-toggle="tab" href="#section3-2" role="tab"
                                onclick="onTab('section3')" aria-controls="section3-2" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-envelope-o"></i></span>
                                <span class="d-none d-sm-block">Gears, Pedals and Stalks</span>
                            </a>
                        </li>
                        <li class="nav-item tab">
                            <a class="nav-link" id="section4-tab-2" data-toggle="tab" href="#section4-2" role="tab"
                                onclick="onTab('section4')" aria-controls="section4-2" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-cog"></i></span>
                                <span class="d-none d-sm-block">Seat & seat adjustments</span>
                            </a>
                        </li>
                        <li class="nav-item tab">
                            <a class="nav-link" id="section6-tab-2" data-toggle="tab" href="#section6-2" role="tab"
                                onclick="onTab('section6')" aria-controls="section6-2" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-cog"></i></span>
                                <span class="d-none d-sm-block">360 view</span>
                            </a>
                        </li>
                        <li class="nav-item tab">
                            <a class="nav-link" id="section5-tab-2" data-toggle="tab" href="#section5-2" role="tab"
                                onclick="onTab('section5')" aria-controls="section5-2" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-cog"></i></span>
                                <span class="d-none d-sm-block">Colors</span>
                            </a>
                        </li>


                    </ul>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane active" id="section0-2" role="tabpanel" aria-labelledby="section0-tab-2">
                            <img src="{{ file_asset('files-car', $car->image) }}"
                                alt='brand-img' class='img-thumbnail'  width='200' height='250'>
                            <img src="{{ file_asset('files-car', $car->image_2) }}"
                                alt='brand-img' class='img-thumbnail'  width='200' height='250'>


                        </div>
                        <div class="tab-pane active" id="section1-2" role="tabpanel" aria-labelledby="section1-tab-2">

                                @foreach($car->carImages as $image)
                                    @if($image->type == 1 && $image->section == 1)
                                        <img src="{{ file_asset('files-car', $image->file_name) }}"
                                        alt='brand-img' class='img-thumbnail' width='200' height='250'>
                                    @endif
                                @endforeach


                        </div>
                        <div class="tab-pane" id="section2-2" role="tabpanel" aria-labelledby="section2-tab-2">
                                @foreach($car->carImages as $image)
                                    @if($image->type == 1 && $image->section == 2)
                                        <img src="{{ file_asset('files-car', $image->file_name) }}"
                                        alt='brand-img' class='img-thumbnail' width='200' height='250'>
                                    @endif
                                @endforeach
                        </div>
                        <div class="tab-pane" id="section3-2" role="tabpanel" aria-labelledby="section3-tab-2">
                            @foreach($car->carImages as $image)
                                    @if($image->type == 1 && $image->section == 3)
                                        <img src="{{ file_asset('files-car', $image->file_name) }}"
                                        alt='brand-img' class='img-thumbnail' width='200' height='250'>
                                    @endif
                                @endforeach
                        </div>
                        <div class="tab-pane" id="section4-2" role="tabpanel" aria-labelledby="section4-tab-2">
                            @foreach($car->carImages as $image)
                                    @if($image->type == 1 && $image->section == 4)
                                        <img src="{{ file_asset('files-car', $image->file_name) }}"
                                        alt='brand-img' class='img-thumbnail' width='200' height='250'>
                                    @endif
                                @endforeach
                        </div>
                        <div class="tab-pane" id="section6-2" role="tabpanel" aria-labelledby="section6-tab-2">
                            @foreach($car->carImages as $image)
                                    @if($image->type == 1 && $image->section == 5)
                                        <img src="{{ file_asset('files-car', $image->file_name) }}"
                                        alt='brand-img' class='img-thumbnail' width='200' height='250'>
                                    @endif
                                @endforeach
                        </div>
                        <div class="tab-pane" id="section5-2" role="tabpanel" aria-labelledby="section5-tab-2">
                            @foreach($car->carImages as $image)
                                    @if($image->type == 1 && $image->color != null)

                                        <img src="{{ file_asset('files-car', $image->file_name) }}"
                                            alt='brand-img' class='img-thumbnail' width='200' height='250'>
                                        <button class="btn-primary" >{{ $colorsAvailable[$image->color] ?? 'Color Not Exist' }}</button>

                                    @endif

                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card card-border card-primary">
        <div class="card-header">
            <div class="m-b-30">
                <h5>Videos</h5>
            </div>
        </div>
        @php
        $videoCount = \App\Models\CarImage::where('car_id',$car->id)->where('type',2)->count();
        @endphp
        <div class="card-body">
        @if($videoCount == 0)
        <p> {{'No Records Found'}}</p>
        @else

            @foreach($car->carImages as $image)
            @if($image->type == 2)
            <div class="row">
        
                <div class="col-md-5">
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Title</label>
                        <div class="col-sm-8">
                            {{ $image->video_title }}
                        </div>
                       
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Description</label>
                        <div class="col-sm-8">
                            {{ $image->video_description }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">View Count</label>
                        <div class="col-sm-8">
                            {{ $image->video_view_count }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Posted Date</label>
                        <div class="col-sm-8">
                            {{ dateFormat($image->video_posted_date) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Posted Media</label>
                        <div class="col-sm-8">
                            {{ $image->video_posted_media }}
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                           <video width="400" height="300" controls style="margin-top: -53px;">
                                <source src="{{ file_asset('files-car',
                                        $image->file_name) }}">
                            </video>
                </div>
                <div class="col-md-2">
                  {{--  <a href="{{ route('admin.car.video.edit', $image->id) }}"
                    class="btn btn-primary waves-effect waves-light"><i class="fa fa-pencil"></i> </a>--}}
                    <a href="#"
                                onclick="showDeleteVideoConfirmation(event)"
                                class="btn btn-danger btn-custom waves-effect waves-light"><i class="fa fa-trash"></i>
                                </a>
                            <form id="video-delete-form"
                                action="{{ route('admin.car.video.delete',['id'=>$image->id])}}"
                                method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                
                </div>
              
            </div>
            <hr>
            @endif

            @endforeach

        </div>
        @endif
    </div>

    @include('admin.car.car-version.index')
    <x-slot name="scripts">
            <script>
            function showDeleteConfirmation(event) {
                event.preventDefault(); // Prevent default link behavior
                $('#deleteConfirmationModal').modal('show'); // Show Bootstrap modal
            }

            function submitDeleteForm() {
                document.querySelector('form#delete-form').submit(); // Submit the form
            }
            function showDeleteVideoConfirmation($event){
                event.preventDefault(); // Prevent default link behavior
                $('#deleteVideoConfirmationModal').modal('show'); // Show Bootstrap modal
            }
            function submitVideoDeleteForm()
            {
                document.querySelector('form#video-delete-form').submit(); // Submit the form
            }
        </script>
    </x-slot>



</x-admin-layout>

