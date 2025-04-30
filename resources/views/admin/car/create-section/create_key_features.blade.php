@php
    $star = '<i class="fa fa-star"></i>';
@endphp
<div class="card card-border card-primary">

    <div class="card-body">
        <div class="row">
            {{--<div class="col-md-4">
            <!-- <span class="badge bg-primary ms-2">key</span> -->
                <x-form-input type="text" field="engine_type" field-name="Engine Type*" value="{{ old('engine_type') }}">
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
                <x-form-input type="text" field="bore_stroke" field-name="Bore x Stroke*" value="{{ old('bore_stroke') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="compression_ratio" field-name="Compression Ratio*" value="{{ old('compression_ratio') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="super_charge" field-name="Super charge">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="gear_box" field-name="Gear Box" value="{{ old('gear_box') }}">
                </x-form-input>
            </div>--}}
            <div class="col-md-4">
                <x-form-input type="text" field="engine_capacity" field-name="Engine Displacement(cc)*" value="{{ old('engine_capacity') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="power" field-name="Power(Bhp)*" value="{{ old('power') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="torque" field-name="Torque(rpm)*" value="{{ old('torque') }}">
                </x-form-input>
            </div>
       {{--     <div class="col-md-4">
                <x-form-input type="text" field="drive_train" field-name="Drivetrain*" value="{{ old('drive_train') }}">
                </x-form-input>
            </div>--}}
            <div class="col-md-12">
                <label class="control-label" for="transmission_types">Transmission Types*</label>
                <div class="row">
                    <div class="form-check form-check-inline col-md-2">
                        <input class="form-check-input" type="checkbox" name="all_transmissions" id="all_transmissions"
                            value="-1" onclick="selectAllTransmissions()" {{ old('all_transmissions') == '-1' ? 'checked' : '' }}>
                        &nbsp;<label class="form-check-label" for="all_transmissions" style="color: black;">
                            All
                        </label>
                    </div>
                    @foreach (config('params.car.transmission_type') as $key => $value)
                    <div class="form-check form-check-inline col-md-2">
                        <input class="form-check-input" type="checkbox" name="transmission_types[]"
                            id="transmission_types{{ $key }}" value="{{ $key }}" onclick="updateAllTransmissions()"
                            {{ in_array($key, old('transmission_types', [])) ? 'checked' : '' }}>
                        &nbsp;<label class="form-check-label" for="transmission_types{{ $key }}" style="color: black;">
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
    </div>
</div>
<div class="card card-border card-primary">

    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <x-form-input type="text" field="seat_capacity" field-name="Seat Capacity*" value="{{ old('seat_capacity') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="no_of_airbags" field-name="No.of Airbags*" value="{{ old('no_of_airbags') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="fuel_tank_capacity" field-name="Fuel Tank Capacity(L)*" value="{{ old('fuel_tank_capacity') }}">
                </x-form-input>
            </div>
         {{--   <div class="col-md-4">
                <x-form-input type="text" field="acceleration" field-name="Acceleration(sec)*" value="{{ old('acceleration') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="top_speed" field-name="Top speed(kmph)*" value="{{ old('top_speed') }}">
                </x-form-input>
            </div>--}}
            <div class="col-md-4">
                <x-form-input type="text" field="mileage" field-name="Mileage(Kmpl/kwh)" value="{{ old('mileage') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="safety_ratings" field-name="Safety ratings*" defaultPrompt="Select">
                    <option selected value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="view_camera" field-name="360 View*" defaultPrompt="Select">
                    @foreach (config('params.car.view-camera') as $value => $label)
                        <option {{ old('view_camera') == $value ? 'Selected' : '' }} value="{{ $value }}">
                            {{ $label }}</option>
                    @endforeach
                </x-form-select>
            </div>
           {{-- <div class="col-md-4">
                <x-form-input type="text" field="emission_norm_complains" field-name="Emission Norm Compliance" value="{{ old('emission_norm_complains') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="fuel_tank_capacity" field-name="Fuel Tank Capacity(L/kW)" value="{{ old('fuel_tank_capacity') }}">
                </x-form-input>
            </div>--}}
            <div class="col-md-12">
                <label class="control-label" for="fuel_types">Fuel Types*</label>
                <div class="row">
                    <div class="form-check form-check-inline col-md-2">
                        <input class="form-check-input" type="checkbox" name="all_fuels" id="all_fuels" value="-1"
                            onclick="selectAllFuels()" {{ old('all_fuels') == '-1' ? 'checked' : '' }}>
                        &nbsp;<label class="form-check-label" for="all_fuels" style="color: black;">
                            All
                        </label>
                    </div>
                    @foreach (config('params.car.fuel_type') as $key => $value)
                    <div class="form-check form-check-inline col-md-2">
                        <input class="form-check-input" type="checkbox" name="fuel_types[]"
                            id="fuel_types{{ $key }}" value="{{ $key }}" onclick="updateAllfuels()"
                            {{ in_array($key, old('fuel_types', [])) ? 'checked' : '' }}>
                        &nbsp;<label class="form-check-label" for="fuel_types{{ $key }}" style="color: black;">
                            {{ $value }}
                        </label>
                    </div>
                    @endforeach
                </div>
                <span class="error" role="alert">
                    @error('fuel_types')
                        {{ $message }}</br>
                    @enderror
                </span>
            </div>

            <div class="col-md-12 mt-3">
                <label class="control-label" for="travel_type">Travel Types*</label>
                <div class="row">
                    <div class="form-check form-check-inline col-md-2">
                        <input class="form-check-input" type="checkbox" name="all_travel" id="all_travel" value="-1"
                            onclick="selectAllTravel()" {{ old('all_travel') == '-1' ? 'checked' : '' }}>
                        &nbsp;<label class="form-check-label" for="all_travel" style="color: black;">
                            All
                        </label>
                    </div>
                    @foreach (config('params.car.travel_type') as $key => $value)
                    <div class="form-check form-check-inline col-md-2">
                        <input class="form-check-input" type="checkbox" name="travel_type[]"
                            id="travel_type{{ $key }}" value="{{ $key }}" onclick="updateAllTravel()"
                            {{ in_array($key, old('travel_type', [])) ? 'checked' : '' }}>
                        &nbsp;<label class="form-check-label" for="travel_type{{ $key }}" style="color: black;">
                            {{ $value }}
                        </label>
                    </div>
                    @endforeach
                </div>
                <span class="error" role="alert">
                    @error('travel_type')
                        {{ $message }}</br>
                    @enderror
                </span>

            </div>
        </div>
    </div>
</div>
{{--<div class="card card-border card-primary">
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
                <x-form-input type="text" field="tuning_radius" field-name="Tuning Radius(m)" value="{{ old('tuning_radius') }}">
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
                <x-form-select field="body_type_id" field-name="Body Type*" id="body_type_id">
                </x-form-select>
                <input type="hidden" id="body_type_id_text" name="body_type_id_text" />
                <span class="error" role="alert" id="body_type_id_error" ></span>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="length" field-name="Length(mm)*" value="{{ old('length') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="width" field-name="Width(mm)*" value="{{ old('width') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="height" field-name="Height(mm)*" value="{{ old('height') }}">
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
                <x-form-input type="text" field="seat_upholstery" field-name="Seat Upholstery*" value="{{ old('seat_upholstery') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="seat_capacity" field-name="Seat Capacity*" value="{{ old('seat_capacity') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="air_condition" field-name="Air Conditioning">
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
                <x-form-input type="text" field="boot_space" field-name="Boot Space(cubic feet)*" value="{{ old('boot_space') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="power_windows" field-name="Power Windows*" value="{{ old('power_windows') }}">
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
                <x-form-select field="adjustable_headlamps" field-name="Ajustable Headlamps">
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
            <div class="col-md-4">
                <x-form-select field="sun_roof" field-name="Sun Roof">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
        </div>
    </div>
</div> --}}

            {{-- <div class="col-md-4">
                <x-form-select field="anti_theft_alarm" field-name="Anti Theft Alarm">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="anti_brake_system" field-name="Anti Brake System">
                    <option selected value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="no_of_airbags" field-name="No.of Airbags*" value="{{ old('no_of_airbags') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="passenger_airbags" field-name="Passenger Airbags*" value="{{ old('passenger_airbags') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="driver_airbags" field-name="Driver Airbags*" value="{{ old('driver_airbags') }}">
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
</div>--}}










