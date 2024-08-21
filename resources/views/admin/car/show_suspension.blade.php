<div class="col-md-12"  style="color:#333;">
    <div class="card card-border card-primary">
        <div class="card-body">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Front Suspension</label>
                    <div class="col-sm-6">
                               {{ $car->engine_type}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Rear Suspension</label>
                    <div class="col-sm-8">
                                {{ $car->no_of_cylinders}}
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Steering Type</label>
                    <div class="col-sm-8">
                                {{ $car->valves_per_cylinder}} cc
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Steering Column</label>
                    <div class="col-sm-8">
                                {{ $car->bore_stroke }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Tuning Radius</label>
                    <div class="col-sm-8">
                                {{ $car->compression_ratio}} 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Front Brake Type</label>
                    <div class="col-sm-8">
                                {{ $car->super_charge}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Rear Brake Type</label>
                    <div class="col-sm-8">
                                {{ $car->transmission_type}} 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Alloy Wheel Front</label>
                    <div class="col-sm-8">
                                {{ $car->engine_capcity}} 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Alloy Wheel Rear</label>
                    <div class="col-sm-8">
                                {{ $car->engine_capcity}} 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Power Steering</label>
                    <div class="col-sm-8">
                                {{ $car->engine_capcity}} 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
                            