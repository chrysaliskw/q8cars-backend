<div class="col-md-12"  style="color:#333;">
    <div class="card card-border card-primary">
        <div class="card-body">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Engine Type</label>
                    <div class="col-sm-6">
                               {{ $car->engine_type}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Anti Theft Alarm</label>
                    <div class="col-sm-8">
                                {{ $car->no_of_cylinders}}
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-4 control-label">No.of Airbags</label>
                    <div class="col-sm-8">
                                {{ $car->valves_per_cylinder}} cc
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Passenger Airbags</label>
                    <div class="col-sm-8">
                                {{ $car->bore_stroke }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Driver Airbags</label>
                    <div class="col-sm-8">
                                {{ $car->compression_ratio}} 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Child Safety Locks</label>
                    <div class="col-sm-8">
                                {{ $car->compression_ratio}} 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
                            