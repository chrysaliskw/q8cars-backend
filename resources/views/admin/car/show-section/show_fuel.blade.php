<div class="col-md-12"  style="color:#333;">
    <div class="card card-border card-primary">
        <div class="card-body">
            <div class="form-horizontal">
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
                    <label class="col-sm-4 control-label">Power</label>
                    <div class="col-sm-8">
                                {{ $carVarient->power}} Bhp
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Torque</label>
                    <div class="col-sm-8">
                                {{ $carVarient->torque }} rpm
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
              
            </div>
        </div>
    </div>
</div>
 
                            