<div class="col-md-12"  style="color:#333;">
    <div class="card card-border card-primary">
        <div class="card-body">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Drive Train</label>
                    <div class="col-sm-8">
                                {{ $carVarient->drive_train}}
                    </div>
                </div> 
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Acceleration</label>
                    <div class="col-sm-8">
                                {{ $carVarient->acceleration}} sec
                    </div>
                </div> 
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Top Speed</label>
                    <div class="col-sm-8">
                                {{ $carVarient->top_speed}} Kmph
                    </div>
                </div> 
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Fuel Type</label>
                    <div class="col-sm-6">
                               {{ config('params.car.fuel_type')[$carVarient->fuel_type]}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Mileage</label>
                    <div class="col-sm-8">
                                {{ $carVarient->mileage}} Kmpl
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Emission Norm Complains</label>
                    <div class="col-sm-8">
                                {{ $carVarient->emission_norm_complains}} 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Fuel Tank Capacity</label>
                    <div class="col-sm-8">
                                {{ $carVarient->fuel_tank_capacity}}
                    </div>
                </div>
                @if($carVarient->fuel)
                @foreach($carVarient->fuel as $spec)
                <div class="form-group row">
                    <label class="col-sm-4 control-label">{{ $spec->specification }}</label>
                    <div class="col-sm-8">
                         @if($spec->input_type == 1)
                            {{ $spec->value }}
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
 
                            