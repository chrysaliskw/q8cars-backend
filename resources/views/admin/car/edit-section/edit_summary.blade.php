<div class="row">
    <div class="col-md-6">
        <x-form-textarea field="why_choose" field-name="Why choose this car?*" rows="6" field-value="{{ $car->why_choose }}"></x-form-textarea>
    </div>
    <div class="col-md-6">
        <x-form-textarea type="text" field="market_introduction" rows="6" field-name="Market Introduction*" field-value="{{  $car->market_introduction }}">
        </x-form-textarea>
    </div>
    <div class="col-md-6">
        <x-form-textarea type="text" field="engine_transmission" rows="6" field-name="Engine and Transmission*" field-value="{{  $car->engine_transmission }}">
        </x-form-textarea>
    </div>
    <div class="col-md-6">
        <x-form-textarea type="text" field="exterior" rows="6" field-name="Exterior*" field-value="{{  $car->exterior }}">
        </x-form-textarea>
    </div>
    <div class="col-md-6">
        <x-form-textarea type="text" field="interior" rows="6" field-name="Interior*" field-value="{{  $car->interior }}">
        </x-form-textarea>
    </div>
    <div class="col-md-6">
        <x-form-textarea type="text" field="safety_features" rows="6" field-name="Safety Features*" field-value="{{  $car->safety_features }}">
        </x-form-textarea>
    </div>
    <div class="col-md-6">
        <x-form-textarea type="text" field="mileage_summary" rows="6" field-name="Mileage Summary*" field-value="{{  $car->mileage_summary }}">
        </x-form-textarea>
    </div>
    <div class="col-md-6">
        <x-form-textarea type="text" field="rivals" rows="6" field-name="Rivals" field-value="{{  $car->rivals }}">
        </x-form-textarea>
    </div>
    
</div>







