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
             
            </div>
        </div>
    </div>
</div>
 
                            