<div class="col-md-12"  style="color:#333;">
    <div class="card card-border card-primary">
        <div class="card-body">
            <div class="form-horizontal">
             <div class="form-group row">
                    <label class="col-sm-4 control-label">Seat Capacity</label>
                    <div class="col-sm-8">
                                {{ $carVarient->seat_capacity}}
                    </div>
                </div>  
              {{-- <div class="form-group row">
                    <label class="col-sm-4 control-label">Seat Upholstery</label>
                    <div class="col-sm-6">
                               {{ $carVarient->seat_upholstery}}
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Air Conditioning</label>
                    <div class="col-sm-8">
                        @if($carVarient->air_condition == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-cross" style="color:red;"></i>
                        @endif
                               
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Wheel Covers</label>
                    <div class="col-sm-8">
                        @if($carVarient->wheel_covers == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                                
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">360 View Camera</label>
                    <div class="col-sm-8">
                        @if($carVarient->view_camera == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                                
                    </div>
                </div>--}}

                @if($carVarient->comfort)
                @foreach($carVarient->comfort as $spec)
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
 
                            