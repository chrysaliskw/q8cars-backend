<div class="col-md-12"  style="color:#333;">
    <div class="card card-border card-primary">
        <div class="card-body">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Boot Space</label>
                    <div class="col-sm-6">
                               {{ $car->boot_space}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Power Windows</label>
                    <div class="col-sm-6">
                               {{ $car->power_windows}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Tachometer</label>
                    <div class="col-sm-8">
                               
                                @if($carVarient->tachometer == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Electronic Multi Tripmeter</label>
                    <div class="col-sm-8">
                             
                                @if($carVarient->electronic_multi_tripmeter == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Digital Odometer</label>
                    <div class="col-sm-8">
                    @if($carVarient->digital_odometer == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                              
                    </div>
                </div>
                @if($carVarient->interior)
                @foreach($carVarient->interior as $spec)
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
 
                            