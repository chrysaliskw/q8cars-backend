<div class="col-md-12"  style="color:#333;">
    <div class="card card-border card-primary">
        <div class="card-body">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Seat Upholstery</label>
                    <div class="col-sm-6">
                               {{ $car->engine_type}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Seat Capacity</label>
                    <div class="col-sm-8">
                                {{ $car->no_of_cylinders}}
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Air Conditioning</label>
                    <div class="col-sm-8">
                                {{ $car->valves_per_cylinder}} cc
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Wheel Covers</label>
                    <div class="col-sm-8">
                                {{ $car->bore_stroke }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">360 View Camera</label>
                    <div class="col-sm-8">
                                {{ $car->compression_ratio}} 
                    </div>
                </div>
            d
            </div>
        </div>
    </div>
</div>
 
                            