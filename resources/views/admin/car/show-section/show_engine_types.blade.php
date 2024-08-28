<div class="col-md-12"  style="color:#333;">
    <div class="card card-border card-primary">
        <div class="card-body">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Engine Type</label>
                    <div class="col-sm-6">
                               {{ $carVarient->engine_type}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">No.of cylinders</label>
                    <div class="col-sm-8">
                                {{ $carVarient->no_of_cylinders}}
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Valves per cylinder</label>
                    <div class="col-sm-8">
                                {{ $carVarient->valves_per_cylinder}} 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Bore x Stroke</label>
                    <div class="col-sm-8">
                                {{ $carVarient->bore_stroke }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Compression Ratio</label>
                    <div class="col-sm-8">
                                {{ $carVarient->compression_ratio}} 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Super charge</label>
                    <div class="col-sm-8">
                        @if($carVarient->super_charge == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                               
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Transmission Type</label>
                    <div class="col-sm-8">
                                {{ config('params.car.transmission_type')[$carVarient->transmission_type]}} 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Engine Capacity</label>
                    <div class="col-sm-8">
                                {{ $carVarient->engine_capacity}} cc
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
                            