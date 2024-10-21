@php
    $star = '<i class="fa fa-star"></i>';
@endphp
<div class="card card-border card-primary">

    <div class="card-body">
        <div class="row">

          {{--  <div class="col-md-4">
            <!-- <span class="badge bg-primary ms-2">key</span> -->
                <x-form-input type="text" field="engine_type" field-name="Engine Type*" value="{{ $carVarient->engine_type }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="no_of_cylinders" field-name="No.of cylinders" value="{{ $carVarient->no_of_cylinders }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="valves_per_cylinder" field-name="Valves per cylinder" value="{{ $carVarient->valves_per_cylinder }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="bore_stroke" field-name="Bore x Stroke*" value="{{ $carVarient->bore_stroke }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="compression_ratio" field-name="Compression Ratio*" value="{{ $carVarient->compression_ratio }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="super_charge" field-name="Super charge">
                    <option selected value="1" <?php if(1 == $carVarient->super_charge){ echo "selected";}?>> Yes</option>
                    <option value="2" <?php if(2 == $carVarient->super_charge){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="gear_box" field-name="Gear Box" value="{{ $carVarient->gear_box }}">
                </x-form-input>
            </div>--}}
            <div class="col-md-4">
                <x-form-input type="text" field="engine_capacity" field-name="Engine Displacement(cc)*" value="{{ $carVarient->engine_capacity }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="power" field-name="Power(Bhp)*" value="{{ $carVarient->power }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="torque" field-name="Torque(rpm)*" value="{{ $carVarient->torque }}">
                </x-form-input>
            </div>

          {{--  <div class="col-md-4">
                <x-form-input type="text" field="drive_train" field-name="Drivetrain*" value="{{ $carVarient->drive_train }}">
                </x-form-input>
            </div>--}}
            <div class="col-md-12">
                <label class="control-label" for="transmission_types">Transmission Types*</label>
                <div class="row">
                    @php
                        $allTransmissionCheck = '';
                        if ($tcount == $selectedTransmissionCount) {
                            $allTransmissionCheck = 'checked';
                        }
                    @endphp
                    <div class="form-check form-check-inline col-md-2">
                            <input class="form-check-input" type="checkbox" name="all_transmissions" id="all_transmissions"
                                value="-1" onclick="selectAllTransmissions()" {{ $allTransmissionCheck }}>
                            &nbsp;<label class="form-check-label" for="all_transmissions" style="color: black;">
                                All
                            </label>
                    </div>
                    @foreach (config('params.car.transmission_type') as $key => $value)
                        @php
                            $attrTCheck = in_array($key, $currentTransmissions) ? 'checked' : '';
                        @endphp
                    <div class="form-check form-check-inline col-md-2">
                                <input class="form-check-input" type="checkbox" name="transmission_types[]"
                                    id="transmission_types{{ $key }}" onclick="updateAllTransmissions()" value="{{ $key }}" {{$attrTCheck}}>
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
    </div>
</div>
<div class="card card-border card-primary">

    <div class="card-body">
        <div class="row">

        <div class="col-md-4">
                <x-form-input type="text" field="seat_capacity" field-name="Seat Capacity*" value="{{ $carVarient->seat_capacity }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="no_of_airbags" field-name="No.of Airbags*" value="{{ $carVarient->no_of_airbags }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="fuel_tank_capacity" field-name="Fuel Tank Capacity*" value="{{ $carVarient->fuel_tank_capacity }}">
                </x-form-input>
            </div>
         {{--   <div class="col-md-4">
                <x-form-input type="text" field="acceleration" field-name="Acceleration(sec)*" value="{{ $carVarient->acceleration }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="top_speed" field-name="Top speed(kmph)*" value="{{ $carVarient->top_speed }}">
                </x-form-input>
            </div>--}}
            <div class="col-md-4">
                <x-form-input type="text" field="mileage" field-name="Mileage*" value="{{ $carVarient->mileage }}">
                </x-form-input>
            </div>
            {{-- <div class="col-md-4">
                <x-form-select field="safety_ratings" field-name="Safety ratings*" defaultPrompt="Select">
                    <option selected value="1" <?php if(1 == $car->safety_ratings){ echo "selected";}?>>1</option>
                    <option value="2" <?php if(2 == $car->safety_ratings){ echo "selected";}?>>2</option>
                    <option value="3" <?php if(3 == $car->safety_ratings){ echo "selected";}?>>3</option>
                    <option value="4" <?php if(4== $car->safety_ratings){ echo "selected";}?>>4</option>
                    <option value="5" <?php if(5 == $car->safety_ratings){ echo "selected";}?>>5</option>
                </x-form-select>
            </div> --}}
            <div class="col-md-4">
                <x-form-select field="safety_ratings" field-name="Safety ratings*" defaultPrompt="Select">
                    <option value="1" {{ $car->safety_ratings == 1 ? 'selected' : '' }}>1</option>
                    <option value="2" {{ $car->safety_ratings == 2 ? 'selected' : '' }}>2</option>
                    <option value="3" {{ $car->safety_ratings == 3 ? 'selected' : '' }}>3</option>
                    <option value="4" {{ $car->safety_ratings == 4 ? 'selected' : '' }}>4</option>
                    <option value="5" {{ $car->safety_ratings == 5 ? 'selected' : '' }}>5</option>
                </x-form-select>
            </div>

          {{--  <div class="col-md-4">
                <x-form-input type="text" field="emission_norm_complains" field-name="Emission Norm Compliance" value="{{ $carVarient->emission_norm_complains }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="fuel_tank_capacity" field-name="Fuel Tank Capacity*" value="{{ $carVarient->fuel_tank_capacity }}">
                </x-form-input>
            </div>--}}
            <div class="col-md-12">
                <label class="control-label" for="fuel_types">Fuel Types*</label>
                <div class="row">
                    @php
                        $allFuelCheck = '';
                        if ($fcount == $selectedFuelCount) {
                            $allFuelCheck = 'checked';
                        }
                    @endphp
                    <div class="form-check form-check-inline col-md-2">
                            <input class="form-check-input" type="checkbox" name="all_fuels" id="all_fuels"
                                value="-1" onclick="selectAllFuels()" {{$allFuelCheck}}>
                            &nbsp;<label class="form-check-label" for="all_fuels" style="color: black;">
                                All
                            </label>
                    </div>
                    @foreach (config('params.car.fuel_type') as $key => $value)
                        @php
                            $attrFCheck = in_array($key, $currentFuels) ? 'checked' : '';
                        @endphp
                    <div class="form-check form-check-inline col-md-2">
                                <input class="form-check-input" type="checkbox" name="fuel_types[]"
                                    id="fuel_types{{ $key }}" onclick="updateAllfuels()" value="{{ $key }}" {{$attrFCheck}}>
                                &nbsp;<label class="form-check-label" for="fuel_types{{ $key }}"
                                    style="color: black;">
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
                <x-form-input type="text" field="front_suspension" field-name="Front Suspension" value="{{ $carVarient->front_suspension }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="rear_suspension" field-name="Rear Suspension" value="{{ $carVarient->rear_suspension }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="steering_type" field-name="Steering Type" value="{{ $carVarient->steering_type }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="steering_column" field-name="Steering Column" value="{{ $carVarient->steering_column }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="tuning_radius" field-name="Tuning Radius" value="{{ $carVarient->tuning_radius }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="front_brake_type" field-name="Front Brake Type" value="{{ $carVarient->front_brake_type }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="rear_brake_type" field-name="Rear Brake Type" value="{{ $carVarient->rear_brake_type }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="alloy_wheel_front" field-name="Alloy Wheel Front">
                    <option selected value="1" <?php if(1 == $carVarient->alloy_wheel_front){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->alloy_wheel_front){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="alloy_wheel_rear" field-name="Alloy Wheel Rear">
                    <option selected value="1" <?php if(1 == $carVarient->alloy_wheel_rear){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->alloy_wheel_rear){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="power_steering" field-name="Power Steering">
                    <option selected value="1" <?php if(1 == $carVarient->power_steering){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->power_steering){ echo "selected";}?>>No</option>
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
                <x-form-input type="text" field="length" field-name="Length(mm)*" value="{{ $carVarient->length }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="width" field-name="Width(mm)*" value="{{ $carVarient->width }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="height" field-name="Height(mm)*" value="{{ $carVarient->height }}">
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
                <x-form-input type="text" field="seat_upholstery" field-name="Seat Upholstery*" value="{{ $carVarient->seat_upholstery }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="seat_capacity" field-name="Seat Capacity*" value="{{ $carVarient->seat_capacity }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="air_condition" field-name="Air Conditioning">
                    <option selected value="1" <?php if(1 == $carVarient->air_condition){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->air_condition){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="wheel_covers" field-name="Wheel Covers">
                    <option selected value="1" <?php if(1 == $carVarient->wheel_covers){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->wheel_covers){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="360_view_camera" field-name="360 View Camera">
                    <option selected value="1" <?php if(1 == $carVarient->view_camera){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->view_camera){ echo "selected";}?>>No</option>
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
                <x-form-input type="text" field="boot_space" field-name="Boot Space*" value="{{ $carVarient->boot_space }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="power_windows" field-name="Power Windows*" value="{{ $carVarient->power_windows }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="tachometer" field-name="Tachometer">
                    <option selected value="1" <?php if(1 == $carVarient->anti_theft_alarm){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(1 == $carVarient->anti_theft_alarm){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="electronic_multi_tripmeter" field-name="Electronic Multi Tripmeter">
                    <option selected value="1" <?php if(1 == $carVarient->electronic_multi_tripmeter){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->electronic_multi_tripmeter){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="digital_odometer" field-name="Digital Odometer">
                    <option selected value="1" <?php if(1 == $carVarient->digital_odometer){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->digital_odometer){ echo "selected";}?>>No</option>
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
                    <option selected value="1" <?php if(1 == $carVarient->LED_Taillights){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->LED_Taillights){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="automatic_headlamps" field-name="Automatic Headlamps">
                    <option selected value="1" <?php if(1 == $carVarient->automatic_headlamps){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->automatic_headlamps){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="adjustable_headlamps" field-name="Ajustable Headlamps">
                    <option selected value="1" <?php if(1 == $carVarient->adjustable_headlamps){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->adjustable_headlamps){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="LED_DRLs" field-name="LED DRLs">
                    <option selected value="1" <?php if(1 == $carVarient->LED_DRLs){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->LED_DRLs){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="Halogen_Headlamps" field-name="Halogen Headlamps">
                    <option selected value="1" <?php if(1 == $carVarient->Halogen_Headlamps){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->Halogen_Headlamps){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="LED_Headlights" field-name="LED Headlights">
                    <option selected value="1" <?php if(1 == $carVarient->LED_Headlights){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->LED_Headlights){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="sun_roof" field-name="Sun Roof">
                    <option selected value="1" <?php if(1 == $carVarient->sun_roof){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->sun_roof){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
        </div>
    </div>
</div> --}}

            {{-- <div class="col-md-4">
                <x-form-select field="anti_theft_alarm" field-name="Anti Theft Alarm">
                    <option selected value="1" <?php if(1 == $carVarient->anti_theft_alarm){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->anti_theft_alarm){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="anti_brake_system" field-name="Anti Brake System">
                    <option selected value="1" <?php if(1 == $carVarient->anti_brake_system){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->anti_brake_system){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="no_of_airbags" field-name="No.of Airbags*" value="{{ $carVarient->no_of_airbags }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="passenger_airbags" field-name="Passenger Airbags*" value="{{ $carVarient->passenger_airbags }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="driver_airbags" field-name="Driver Airbags*" value="{{ $carVarient->driver_airbags }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="child_safety_locks" field-name="Child Safety Locks">
                    <option selected value="1" <?php if(1 == $carVarient->child_safety_locks){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->child_safety_locks){ echo "selected";}?>>No</option>
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
                    <option selected value="1" <?php if(1 == $carVarient->integrated_antenna){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->integrated_antenna){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="apple_car_play" field-name="Apple CarPlay">
                    <option selected value="1" <?php if(1 == $carVarient->apple_car_play){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->apple_car_play){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="touch_screen" field-name="Touch Screen">
                    <option selected value="1" <?php if(1 == $carVarient->touch_screen){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->touch_screen){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="speakers_rear" field-name="Speakers Rear">
                    <option selected value="1" <?php if(1 == $carVarient->speakers_rear){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->speakers_rear){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="speakers_front" field-name="Speakers Front">
                    <option selected value="1" <?php if(1 == $carVarient->speakers_front){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->speakers_front){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="radio" field-name="Radio">
                    <option selected value="1" <?php if(1 == $carVarient->radio){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->radio){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="android_auto" field-name="Android Auto">
                    <option selected value="1" <?php if(1 == $carVarient->android_auto){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->android_auto){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="digital_clock" field-name="Digital Clock">
                    <option selected value="1" <?php if(1 == $carVarient->digital_clock){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->digital_clock){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="usb_charger" field-name="USB & Auxiliary input">
                    <option selected value="1" <?php if(1 == $carVarient->usb_charger){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->usb_charger){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-select field="bluetooth" field-name="Bluetooth Connectivity">
                    <option selected value="1" <?php if(1 == $carVarient->bluetooth){ echo "selected";}?>>Yes</option>
                    <option value="2" <?php if(2 == $carVarient->bluetooth){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
        </div>
    </div>
</div>--}}










