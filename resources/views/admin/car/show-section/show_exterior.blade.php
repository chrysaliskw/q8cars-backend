<div class="col-md-12"  style="color:#333;">
    <div class="card card-border card-primary">
        <div class="card-body car-add-image">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label class="col-sm-4 control-label">LED Taillights</label>
                    <div class="col-sm-6">
                               
                               @if($carVarient->LED_Taillights == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Automatic Headlamps</label>
                    <div class="col-sm-8">
                            
                    @if($carVarient->Automatic_Headlamps == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                              
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-4 control-label">LED DRLs</label>
                    <div class="col-sm-8">
                               
                                @if($carVarient->LED_DRLs == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Halogen Headlamps</label>
                    <div class="col-sm-8">
                              
                    @if($carVarient->Halogen_Headlamps == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                                
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Ajustable Headlamps</label>
                    <div class="col-sm-8">
                               
                        @if($carVarient->adjustable_headlamps == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">LED Headlights</label>
                    <div class="col-sm-8">
                               
                                @if($carVarient->LED_Headlights == 1)
                        <i class="fa fa-check" style="color:green;"></i>
                        @else
                        <i class="fa fa-times" style="color:red;"></i>
                        @endif
                    </div>
                </div>
                @if($carVarient->exterior)
                @foreach($carVarient->exterior as $spec)
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
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Sun Roof</label>
                    <div class="col-sm-8">
                               
                        @if($carVarient->sun_roof == 1)
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
 
                            