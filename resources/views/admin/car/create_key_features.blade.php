<div class="card card-border card-primary">
    <div class="card-header"> 
        <div class="m-b-30">
            <h5>Pricing Details (KWD)</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <x-form-input type="text" field="base_ex_showroom_price" field-name="Ex-Showroom Price" value="{{ old('ex_showroom_price') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="base_on_road_price" field-name="On Road Price" value="{{ old('base_on_road_price') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="base_finance_available" field-name="Finance Available" value="{{ old('base_finance_available') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="base_insurance" field-name="Insurance" value="{{ old('base_insurance') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="base_service_charge" field-name="Service Cost(Avg of 5 Years)" value="{{ old('base_service_charge') }}">
                </x-form-input>
            </div>
        </div>
    </div>
</div>
<div class="card card-border card-primary">
    <div class="card-header"> 
        <div class="m-b-30">
            <h5>Engine and Transmission</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <x-form-input type="text" field="engine_type" field-name="Engine Type" value="{{ old('engine_type') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="no_of_cylinders" field-name="No.of cylinders" value="{{ old('no_of_cylinders') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="valves_per_cylinder" field-name="Valves per cylinder" value="{{ old('valves_per_cylinder') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="bore_stroke" field-name="Bore x Stroke" value="{{ old('bore_stroke') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="compression_ratio" field-name="Compression Ratio" value="{{ old('compression_ratio') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="super_charge" field-name="Super charge">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="transmission_type" field-name="Transmission Type" defaultPrompt="Select transmission type">
                    @foreach (config('params.car.transmission_type') as $value => $label)
                        <option {{ old('transmission_type') == $value ? 'Selected' : '' }} value="{{ $value }}">
                            {{ $label }}</option>
                    @endforeach
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="engine_capacity" field-name="Engine Capacity" value="{{ old('engine_capacity') }}">
                </x-form-input>
            </div>
        </div>
    </div>
</div>
<div class="card card-border card-primary">
    <div class="card-header"> 
        <div class="m-b-30">
            <h5>Fuel and Performance</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">  
            <div class="col-md-4">
                <x-form-select field="fuel_type" field-name="Fuel Type" defaultPrompt="Select fuel type">
                    @foreach (config('params.car.fuel_type') as $value => $label)
                        <option {{ old('fuel_type') == $value ? 'Selected' : '' }} value="{{ $value }}">
                            {{ $label }}</option>
                    @endforeach
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="base_mileage" field-name="Mileage" value="{{ old('base_mileage') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="base_power" field-name="Power" value="{{ old('base_power') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="base_torque" field-name="Torque" value="{{ old('base_torque') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="emission_norm_complains" field-name="Emission Norm Complains" value="{{ old('emission_norm_complains') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="base_fuel_tank_capacity" field-name="Fuel Tank Capacity" value="{{ old('base_fuel_tank_capacity') }}">
                </x-form-input>
            </div>
        </div>
    </div>
</div>
<div class="card card-border card-primary">
    <div class="card-header"> 
        <div class="m-b-30">
            <h5>Suspension, Steering and Brake</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row"> 
            <div class="col-md-4">
                <x-form-input type="text" field="front_suspension" field-name="Front Suspension" value="{{ old('front_suspension') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="rear_suspension" field-name="Rear Suspension" value="{{ old('rear_suspension') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="steering_type" field-name="Steering Type" value="{{ old('steering_type') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="steering_column" field-name="Steering Column" value="{{ old('steering_column') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="tuning_radius" field-name="Tuning Radius" value="{{ old('tuning_radius') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="front_brake_type" field-name="Front Brake Type" value="{{ old('front_brake_type') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="rear_brake_type" field-name="Rear Brake Type" value="{{ old('rear_brake_type') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="alloy_wheel_front" field-name="Alloy Wheel Front">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="alloy_wheel_rear" field-name="Alloy Wheel Rear">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="power_steering" field-name="Power Steering">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
        </div>
    </div>
</div>
<div class="card card-border card-primary">
    <div class="card-header"> 
        <div class="m-b-30">
            <h5>Dimension Capacity</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row"> 
            <div class="col-md-4">
                <x-form-select field="body_type_id" field-name="Body Type" id="body_type_id">
                </x-form-select>
                <input type="hidden" id="body_type_id_text" name="body_type_id_text" />
                <span class="error" role="alert" id="body_type_id_error" ></span>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="base_length" field-name="Length(mm)" value="{{ old('base_length') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="base_width" field-name="Width(mm)" value="{{ old('base_width') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="base_height" field-name="Height(mm)" value="{{ old('base_height') }}">
                </x-form-input>
            </div>
        </div>
    </div>
</div>
<div class="card card-border card-primary">
    <div class="card-header"> 
        <div class="m-b-30">
            <h5>Comfort Convinience</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row"> 
            <div class="col-md-4">
                <x-form-input type="text" field="base_seat_upholstery" field-name="Seat Upholstery" value="{{ old('seat_upholstery') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="base_seat_capacity" field-name="Seat Capacity" value="{{ old('seat_capacity') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="base_air_conditioner" field-name="Air Conditioning">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="wheel_covers" field-name="Wheel Covers">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="360_view_camera" field-name="360 View Camera">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
        </div>
    </div>
</div>
<div class="card card-border card-primary">
    <div class="card-header"> 
        <div class="m-b-30">
            <h5>Interior</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row"> 
            <div class="col-md-4">
                <x-form-input type="text" field="base_boot_space" field-name="Boot Space" value="{{ old('base_boot_space') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="tachometer" field-name="Tachometer">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="electronic_multi_tripmeter" field-name="Electronic Multi Tripmeter">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="digital_odometer" field-name="Digital Odometer">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
        </div>
    </div>
</div>
<div class="card card-border card-primary">
    <div class="card-header"> 
        <div class="m-b-30">
            <h5>Exterior</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row"> 
            <div class="col-md-4">
                <x-form-select field="LED_Taillights" field-name="LED Taillights">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="automatic_headlamps" field-name="Automatic Headlamps">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="LED_DRLs" field-name="LED DRLs">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="Halogen_Headlamps" field-name="Halogen Headlamps">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="LED_Headlights" field-name="LED Headlights">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
        </div>
    </div>
</div>
<div class="card card-border card-primary">
    <div class="card-header"> 
        <div class="m-b-30">
            <h5>Safety</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row"> 
            <div class="col-md-4">
                <x-form-input type="text" field="base_engine_type" field-name="Engine Type" value="{{ old('base_engine_type') }}">
                </x-form-input>
            </div>
         
            <div class="col-md-4">
                <x-form-select field="anti_theft_alarm" field-name="Anti Theft Alarm">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="no_of_airbags" field-name="No.of Airbags" value="{{ old('no_of_airbags') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="passenger_airbags" field-name="Passenger Airbags" value="{{ old('passenger_airbags') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="driver_airbags" field-name="Driver Airbags" value="{{ old('driver_airbags') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="child_safety_locks" field-name="Child Safety Locks">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
        </div>
    </div>
</div>
<div class="card card-border card-primary">
    <div class="card-header"> 
        <div class="m-b-30">
            <h5>Entertainment and Comminication</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row"> 
            <div class="col-md-4">
                <x-form-select field="integrated_antenna" field-name="Integrated Antenna">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="apple_car_play" field-name="Apple CarPlay">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="touch_screen" field-name="Touch Screen">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="speakers_rear" field-name="Speakers Rear">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="speakers_front" field-name="Speakers Front">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="radio" field-name="Radio">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="android_auto" field-name="Android Auto">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="digital_clock" field-name="Digital Clock">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="usb_charger" field-name="USB & Auxiliary input">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="bluetooth" field-name="Bluetooth Connectivity">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
        </div>
    </div>
</div>
   
  
 







