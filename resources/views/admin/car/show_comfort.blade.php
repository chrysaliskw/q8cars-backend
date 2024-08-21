<div class="col-md-12"  style="color:#333;">
    <div class="card card-border card-primary">
        <div class="card-body">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Seat Upholstery</label>
                    <div class="col-sm-6">
                               {{ $carVarient->seat_pholstery}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Seat Capacity</label>
                    <div class="col-sm-8">
                                {{ $carVarient->seat_capacity}}
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Air Conditioning</label>
                    <div class="col-sm-8">
                                {{ $carVarient->air_condition == 1 }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Wheel Covers</label>
                    <div class="col-sm-8">
                                {{ $carVarient->wheel_covers }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">360 View Camera</label>
                    <div class="col-sm-8">
                               
                    </div>
                </div>
            
            </div>
        </div>
    </div>
</div>
 
                            