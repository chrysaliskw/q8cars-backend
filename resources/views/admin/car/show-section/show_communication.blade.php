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
                    @if($carVarient->apply_carplay == 1)
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
                    @if($carVarient->andriod_auto == 1)
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
            
                    @if($carVarient->usb_connectivity == 1)
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
            </div>
        </div>
    </div>
</div>
 
                            