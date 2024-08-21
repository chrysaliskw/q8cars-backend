<div class="col-md-12"  style="color:#333;">
    <div class="card card-border card-primary">
        <div class="card-body">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Fuel Type</label>
                    <div class="col-sm-6">
                               {{ $car->engine_type}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Mileage</label>
                    <div class="col-sm-8">
                                {{ $car->no_of_cylinders}}
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Power</label>
                    <div class="col-sm-8">
                                {{ $car->valves_per_cylinder}} cc
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Torque</label>
                    <div class="col-sm-8">
                                {{ $car->bore_stroke }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Emission Norm Complains</label>
                    <div class="col-sm-8">
                                {{ $car->compression_ratio}} 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Fuel Tank Capacity</label>
                    <div class="col-sm-8">
                                {{ $car->super_charge}}
                    </div>
                </div>
              
            </div>
        </div>
    </div>
</div>
 
                            