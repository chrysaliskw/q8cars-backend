<div class="col-md-12"  style="color:#333;">
    <div class="card card-border card-primary">
        <div class="card-body">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Front Suspension</label>
                    <div class="col-sm-6">
                               {{ $carVarient->front_suspension}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Rear Suspension</label>
                    <div class="col-sm-8">
                                {{ $carVarient->rear_suspension}}
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Steering Type</label>
                    <div class="col-sm-8">
                                {{ $carVarient->steering_type}} 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Steering Column</label>
                    <div class="col-sm-8">
                                {{ $carVarient->steering_column }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Tuning Radius</label>
                    <div class="col-sm-8">
                                {{ $carVarient->tuning_radius}} 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Front Brake Type</label>
                    <div class="col-sm-8">
                                {{ $carVarient->front_brake_type}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Rear Brake Type</label>
                    <div class="col-sm-8">
                                {{ $carVarient->rear_brake_type}} 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Alloy Wheel Front</label>
                    <div class="col-sm-8">
                           
                                
                                @if($carVarient->alloy_wheel_front == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Alloy Wheel Rear</label>
                    <div class="col-sm-8">
                             
                                @if($carVarient->alloy_wheel_rear == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Power Steering</label>
                    <div class="col-sm-8">
                          
                            @if($carVarient->power_steering == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
                            