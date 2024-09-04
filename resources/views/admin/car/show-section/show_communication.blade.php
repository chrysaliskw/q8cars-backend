<div class="col-md-12"  style="color:#333;">
    <div class="card card-border card-primary">
        <div class="card-body">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Integrated Antenna</label>
                    <div class="col-sm-6">
                    @if($carVarient->integrated_antenna == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                  
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Apple CarPlay</label>
                    <div class="col-sm-8">
                    @if($carVarient->apple_car_play == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                   
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Touch Screen</label>
                    <div class="col-sm-8">
                
                    @if($carVarient->touch_screen == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Speakers Rear</label>
                    <div class="col-sm-8">
                   
                    @if($carVarient->speakers_rear == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Speakers Front</label>
                    <div class="col-sm-8">
                   
                    @if($carVarient->speakers_front == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Radio</label>
                    <div class="col-sm-8">
                
                    @if($carVarient->radio == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Android Auto</label>
                    <div class="col-sm-8">
                    @if($carVarient->android_auto == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif 
                  
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Digital Clock</label>
                    <div class="col-sm-8">
                    @if($carVarient->digital_clock == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif 
                   
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">USB & Auxiliary input</label>
                    <div class="col-sm-8">
            
                    @if($carVarient->usb_charger == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Bluetooth Connectivity</label>
                    <div class="col-sm-8">
                
                    
                    @if($carVarient->bluetooth == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif 
                    </div>
                </div>
                @if($carVarient->entertainment)
                @foreach($carVarient->entertainment as $spec)
                <div class="form-group row">
                    <label class="col-sm-4 control-label">{{ $spec->specification }}</label>
                    <div class="col-sm-8">
                         @if($spec->input_type == 1)
                            {{ $spec->value }}
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
 
                            