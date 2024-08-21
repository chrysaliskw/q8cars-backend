<div class="card card-border card-primary">
    <div class="card-header"> 
        <div class="m-b-30">
            <h5></h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <x-form-select field="brand_id" field-name="Brand" id="brand_id">
                </x-form-select>
                <input type="hidden" id="brand_id_text" name="brand_id_text" />
                <span class="error" role="alert" id="brand_id_error" ></span>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="model_name" field-name="Model Name" value="{{ old('model_name') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="varient_name" field-name="Base Varient Name" value="{{ old('varient_name') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="is_just_launched" field-name="Is Just Launched ?" defaultPrompt="Select">
                    <option value="1">Yes</option>
                    <option selected value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="sort_order" field-name="Sort Order" value="{{ old('sort_order') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                    @foreach (config('params.car.status') as $value => $label)
                        <option {{ old('status') == $value ? 'Selected' : '' }} value="{{ $value }}">
                            {{ $label }}</option>
                    @endforeach
                </x-form-select>
            </div>
        </div>
    </div>
</div>
<div class="card card-border card-primary">
    <div class="card-header"> 
        <div class="m-b-30">
            <h5>Pricing Details (KWD)</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <x-form-input type="text" field="ex_showroom_price" field-name="Ex-Showroom Price" value="{{ old('ex_showroom_price') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="on_road_price" field-name="On Road Price" value="{{ old('on_road_price') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="finance_available" field-name="Finance Available" value="{{ old('finance_available') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="insurance" field-name="Insurance" value="{{ old('insurance') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="service_charge" field-name="Service Cost(Avg of 5 Years)" value="{{ old('service_charge') }}">
                </x-form-input>
            </div>
        </div>
    </div>
</div>
<div class="card card-border card-primary">
    <div class="card-header"> 
        <div class="m-b-30">
            <h5>Key Features</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <x-form-select field="air_condition" field-name="Air Condition" defaultPrompt="Select">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="length" field-name="Length(mm)" value="{{ old('length') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="width" field-name="Width(mm)" value="{{ old('width') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="height" field-name="Height(mm)" value="{{ old('height') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="boot_space" field-name="Boot Space(L)" value="{{ old('boot_space') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="power_windows" field-name="Power Windows" value="{{ old('power_windows') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="fuel_tank_capacity" field-name="Fuel Tank Capacity(L)" value="{{ old('fuel_tank_capacity') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="seat_upholstery" field-name="Seat Upholstery" value="{{ old('seat_upholstery') }}">
                </x-form-input>
            </div>
        </div>
    </div>
</div>
<div class="card card-border card-primary">
    <div class="card-header"> 
        <div class="m-b-30">
            <h5>Key Specifications</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <label class="control-label" for="fuel_types">Fuel Types</label>
                <div class="row">
                    <div class="form-check form-check-inline col-md-2">
                            <input class="form-check-input" type="checkbox" name="all_fuels" id="all_fuels"
                                value="-1" onclick="selectAllFuels()">
                            &nbsp;<label class="form-check-label" for="all_fuels" style="color: black;">
                                All 
                            </label>
                    </div>
                    @foreach (config('params.car.fuel_type') as $key => $value)
                    <div class="form-check form-check-inline col-md-2">
                                <input class="form-check-input" type="checkbox" name="fuel_types[]"
                                    id="fuel_types{{ $key }}" value="{{ $key }}">
                                &nbsp;<label class="form-check-label" for="fuel_types{{ $key }}"
                                    style="color: black;">
                                    {{ $value }}
                                </label>
                    </div>  
                    @endforeach
                </div>
                <span class="error" role="alert">
                        @error('fuel_type')
                            {{ $message }}</br>
                        @enderror
                </span>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <label class="control-label" for="transmission_types">Transmission Types</label>
                <div class="row">
                    <div class="form-check form-check-inline col-md-2">
                            <input class="form-check-input" type="checkbox" name="all_transmisssions" id="all_transmisssions"
                                value="-1" onclick="selectAllTransmissions()">
                            &nbsp;<label class="form-check-label" for="all_transmisssions" style="color: black;">
                                All 
                            </label>
                    </div>
                    @foreach (config('params.car.transmission_type') as $key => $value)
                    <div class="form-check form-check-inline col-md-2">
                                <input class="form-check-input" type="checkbox" name="transmission_types[]"
                                    id="transmission_types{{ $key }}" value="{{ $key }}">
                                &nbsp;<label class="form-check-label" for="transmission_types{{ $key }}"
                                    style="color: black;">
                                    {{ $value }}
                                </label>
                    </div>  
                    @endforeach
                </div>
                <span class="error" role="alert">
                        @error('transmission_types')
                            {{ $message }}</br>
                        @enderror
                </span>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <label class="control-label" for="profession">Professions</label>
                <div class="row">
                    <div class="form-check form-check-inline col-md-2">
                            <input class="form-check-input" type="checkbox" name="all_profession" id="all_profession"
                                value="-1" onclick="selectAllProfession()">
                            &nbsp;<label class="form-check-label" for="all_profession" style="color: black;">
                                All 
                            </label>
                    </div>
                    @foreach (config('params.professions') as $key => $value)
                    <div class="form-check form-check-inline col-md-2">
                                <input class="form-check-input" type="checkbox" name="professions[]"
                                    id="professions{{ $key }}" value="{{ $key }}">
                                &nbsp;<label class="form-check-label" for="professions{{ $key }}"
                                    style="color: black;">
                                    {{ $value }}
                                </label>
                    </div>  
                    @endforeach
                </div>
                <span class="error" role="alert">
                        @error('professions')
                            {{ $message }}</br>
                        @enderror
                </span>
            </div>
        </div>
        <br>
        <div class="row">

            <div class="col-md-4">
                <x-form-select field="safety_ratings" field-name="Safety ratings" defaultPrompt="Select">
                    <option selected value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="engine_capacity" field-name="Engine Displacement(cc)" value="{{ old('engine_capacity') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="power" field-name="Power(Bhp)" value="{{ old('power') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="torque" field-name="Torque(rpm)" value="{{ old('torque') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="drive_train" field-name="Drive train" value="{{ old('drive_train') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="acceleration" field-name="Acceleration(sec)" value="{{ old('acceleration') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="top_speed" field-name="Top speed(kmph)" value="{{ old('top_speed') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="mileage" field-name="Avg. Mileage(klmp)" value="{{ old('mileage') }}">
                </x-form-input>
            </div>
        </div>
    </div>
</div>




















