<div class="col-md-12"  style="color:#333;">
    <div class="card card-border card-primary">
        <div class="card-body">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Body Type</label>
                    <div class="col-sm-6">
                            {{ $carVarient->bodyType->name}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Length</label>
                    <div class="col-sm-8">
                                {{ $car->length}} mm
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Width</label>
                    <div class="col-sm-8">
                                {{ $car->width}} mm
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Height</label>
                    <div class="col-sm-8">
                                {{ $car->height }} mm
                    </div>
                </div>
                @if($carVarient->dimension)
                @foreach($carVarient->dimension as $spec)
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
 
                            