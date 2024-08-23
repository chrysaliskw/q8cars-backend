<div class="col-md-12"  style="color:#333;">
    <div class="card card-border card-primary">
        <div class="card-body">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label class="col-sm-4 control-label">LED Taillights</label>
                    <div class="col-sm-6">
                               {{ ($carVarient->LED_Taillights == 1) ? 'YES' : 'NO'}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Automatic Headlamps</label>
                    <div class="col-sm-8">
                                {{ ($carVarient->Automatic_Headlamps == 1) ? 'YES' : 'NO'}}
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-4 control-label">LED DRLs</label>
                    <div class="col-sm-8">
                                {{ ($carVarient->LED_DRLs == 1) ? 'YES' : 'NO'}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Halogen Headlamps</label>
                    <div class="col-sm-8">
                                {{ ($carVarient->Halogen_Headlamps == 1) ? 'YES' : 'NO' }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">LED Headlights</label>
                    <div class="col-sm-8">
                                {{ ($carVarient->LED_Headlights == 1) ? 'YES' : 'NO'}} 
                    </div>
                </div>
            
            </div>
        </div>
    </div>
</div>
 
                            