<div class="col-md-12"  style="color:#333;">
    <div class="card card-border card-primary">
        <div class="card-body">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label class="col-sm-4 control-label">LED Taillights</label>
                    <div class="col-sm-6">
                               {{ $car->engine_type}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Automatic Headlamps</label>
                    <div class="col-sm-8">
                                {{ $car->no_of_cylinders}}
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-4 control-label">LED DRLs</label>
                    <div class="col-sm-8">
                                {{ $car->valves_per_cylinder}} cc
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Halogen Headlamps</label>
                    <div class="col-sm-8">
                                {{ $car->bore_stroke }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">LED Headlights</label>
                    <div class="col-sm-8">
                                {{ $car->compression_ratio}} 
                    </div>
                </div>
            
            </div>
        </div>
    </div>
</div>
 
                            