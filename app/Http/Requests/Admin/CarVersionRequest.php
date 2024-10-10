<?php

namespace App\Http\Requests\Admin;

use App\Models\BodyType;
use App\Models\Brand;
use App\Models\Car;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RegexAlphaNumSpaceHyphen;

class CarVersionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

     /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            // 'all_fuels' => $this->all_fuels ? 1 : 0,
            // 'all_transmissions' => $this->all_transmissions ? 1 : 0,
            // 'all_profession' => $this->all_profession ? 1 : 0,
            // 'date_1' => date('Y-m-d', strtotime($this->date_1)),
            // 'date_2' => date('Y-m-d', strtotime($this->date_2)),
            // 'date_3' => date('Y-m-d', strtotime($this->date_3)),
        ]);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // Basic Info

            'varient_name' => 'required|string|max:100',
            'status' => ['required', Rule::in(array_keys(config('params.car.status')))],

            'ex_showroom_price' => 'required|numeric|min:0|max:99999999',
            'on_road_price' => 'required|numeric|min:0|max:99999999',
            'finance_available' => 'required|numeric|min:0|max:99999999',
            'insurance' => 'required|numeric|min:0|max:99999999',
            'service_charge' => 'required|numeric|min:0|max:99999999',

            // 'engine_type' => 'required|string',
            // 'no_of_cylinders' => 'nullable|integer',
            // 'valves_per_cylinder' => 'nullable|integer',
            // 'bore_stroke' => 'required|string',
            // 'compression_ratio' => 'required|string',
            // 'super_charge' => 'nullable|integer',
            // 'gear_box' => 'nullable|string',
            'engine_capacity' => 'required|numeric|min:1|max:999999',
            'power' => 'required|numeric|min:1|max:99999',
            'torque' => 'required|numeric|min:1|max:99999',
            'transmission_type' => ['required', Rule::in(array_keys(config('params.car.transmission_type')))],
            // 'drive_train' => 'required|string',
            // 'acceleration' => 'required|string',
            // 'top_speed' => 'required|string',
            'mileage' => 'required|numeric',
            // 'emission_norm_complains' => 'nullable|string',
            'fuel_tank_capacity' => 'required|numeric|min:1|max:999999',
            'fuel_type' => ['required', Rule::in(array_keys(config('params.car.fuel_type')))],
            // 'front_suspension' => 'nullable|string',
            // 'rear_suspension' => 'nullable|string',
            // 'steering_type' => 'nullable|string',
            // 'steering_column' => 'nullable|string',
            // 'tuning_radius' => 'nullable|numeric',
            // 'front_brake_type' => 'nullable|string',
            // 'rear_brake_type' => 'nullable|string',
            // 'alloy_wheel_front' => 'nullable|integer',
            // 'alloy_wheel_rear' => 'nullable|integer',
            // 'power_steering' => 'nullable|integer',
            'body_type_id' => [
                'required',
                Rule::exists(BodyType::class, 'id')->where(function ($query) {
                    return $query->where('status', BodyType::STATUS_ACTIVE);
                })
            ],
            // 'length' => 'required|numeric|min:1|max:9999999',
            // 'width' => 'required|numeric|min:1|max:9999999',
            // 'height' => 'required|numeric|min:1|max:9999999',
            // 'seat_upholstery' => 'required|string',
            'seat_capacity' => 'required|integer',
            // 'air_condition' => 'nullable|integer',
            // 'wheel_covers' => 'nullable|integer',
            // 'alloy_wheels' => 'nullable|integer',
            // '360_view_camera' => 'nullable|integer',
            // 'boot_space' => 'required|numeric|min:1|max:999999',
            // 'power_windows' => 'required|string',

            // 'tachometer' => 'nullable|integer',
            // 'electronic_multi_tripmeter' => 'nullable|integer',
            // 'digital_odometer' => 'nullable|integer',
            // 'LED_Taillights' => 'nullable|integer',
            // 'automatic_headlamps' => 'nullable|integer',
            // 'adjustable_headlamps' => 'nullable|integer',
            // 'LED_DRLs' => 'nullable|integer',
            // 'Halogen_Headlamps' => 'nullable|integer',
            // 'LED_Headlights' => 'nullable|integer',
            // 'sun_roof' => 'nullable|integer',
            'safety_ratings' => ['required', Rule::in([1,2,3,4,5])],
            // 'anti_theft_alarm' => 'nullable|integer',
            // 'anti_brake_system' => 'nullable|integer',
            'no_of_airbags' => 'required|integer',
            // 'passenger_airbags' => 'required|integer',
            // 'driver_airbags' => 'required|integer',
            // 'child_safety_locks' => 'nullable|integer',
            // 'integrated_antenna' => 'nullable|integer',
            // 'apple_car_play' => 'nullable|integer',
            // 'touch_screen' => 'nullable|integer',
            // 'speakers_rear' => 'nullable|integer',
            // 'speakers_front' => 'nullable|integer',
            // 'radio' => 'nullable|integer',
            // 'android_auto' => 'nullable|integer',
            // 'digital_clock' => 'nullable|integer',
            // 'usb_charger' => 'nullable|integer',
            // 'bluetooth' => 'nullable|integer',

        ];
    }

    public function messages()
    {
        return [
            'required' => 'This field is required.',
        ];
    }



}
