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
                    <label class="col-sm-4 control-label">Anti Theft Alarm</label>
                    <div class="col-sm-8">
                                {{ $carVarient->anti_theft_alarm}}
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-4 control-label">No.of Airbags</label>
                    <div class="col-sm-8">
                                {{ $carVarient->no_of_airbags}} 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Passenger Airbags</label>
                    <div class="col-sm-8">
                                {{ $carVarient->passenger_airbags }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Driver Airbags</label>
                    <div class="col-sm-8">
                                {{ $carVarient->driver_airbags}} 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Child Safety Locks</label>
                    <div class="col-sm-8">
                                {{ $carVarient->child_safety_locks}} 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
                            