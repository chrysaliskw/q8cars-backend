<div class="col-md-12"  style="color:#333;">
    <div class="card card-border card-primary">
        <div class="card-body">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label class="col-sm-4 control-label">No.of Airbags</label>
                    <div class="col-sm-8">
                                {{ $carVarient->no_of_airbags}} 
                    </div>
                </div>
              <div class="form-group row">
                    <label class="col-sm-4 control-label">Safety Ratings</label>
                    <div class="col-sm-8">
                        @for($i = 1; $i <= $car->safety_ratings ; $i++)
                            <i class="fa fa-star" style="color: red;"></i>
                        @endfor
                    </div>
                </div>
                {{-- <div class="form-group row">
                    <label class="col-sm-4 control-label">Anti Theft Alarm</label>
                    <div class="col-sm-8">
                               
                                @if($carVarient->anti_theft_alarm == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Anti Brake System</label>
                    <div class="col-sm-8">
                               
                                @if($carVarient->anti_brake_system == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
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
                    @if($carVarient->child_safety_locks == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                                
                    </div>
                </div>--}}
                @if($carVarient->safety)
                @foreach($carVarient->safety as $spec)
                <div class="form-group row">
                    <label class="col-sm-4 control-label">{{ $spec->specification }}</label>
                    <div class="col-sm-8">
                         @if($spec->input_type == 1)
                         {{ $spec->value}} {{$spec->unit}}
                         @else
                            @if($spec->value == 1)
                                <i class="fa fa-check" style="color:green;"></i>
                            @else
                                <i class="fa fa-times" style="color:red;"></i>
                            @endif
                         @endif      
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
 
                            