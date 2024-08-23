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
                    <label class="col-sm-4 control-label">Tachometer</label>
                    <div class="col-sm-8">
                                {{ ($carVarient->tachometer == 1) ? 'YES' : 'NO'}}
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Electronic Multi Tripmeter</label>
                    <div class="col-sm-8">
                                {{ ($car->electronic_multi_tripmeter == 1) ? 'YES' : 'NO'}} 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Digital Odometer</label>
                    <div class="col-sm-8">
                                {{ ($car->digital_odometer == 1) ? 'YES' : 'NO' }}
                    </div>
                </div>
        
         
            </div>
        </div>
    </div>
</div>
 
                            