<?php

namespace App\Http\Requests\Admin;

use App\Models\BodyType;
use App\Models\Brand;
use App\Models\Car;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RegexAlphaNumSpaceHyphen;

class CarRequest extends FormRequest
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
            'all_fuels' => $this->all_fuels ? 1 : 0,
            'all_transmisssions' => $this->all_transmisssions ? 1 : 0,
            'all_profession' => $this->all_profession ? 1 : 0
        ]);
    }
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    { 
        if ($this->isMethod('post')) {
            return $this->createRules();
        }
    
        return $this->updateRules();
    }
    private function createRules()
    {
     
        return [
            // Basic Info
           'brand_id' => [
                'required',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                })
            ],
            'model_name' => 'required|string|max:100',
            'varient_name' => 'required|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_just_launched' => ['required', Rule::in([Car::JUST_LAUNCHED, Car::NOT_JUST_LAUNCHED])],
            'status' => ['required', Rule::in(array_keys(config('params.car.status')))],
            // Pricing 
            'ex_showroom_price' => 'required|numeric|min:0|max:99999999',
            'on_road_price' => 'required|numeric|min:0|max:99999999',
            'finance_available' => 'required|numeric|min:0|max:99999999',
            'insurance' => 'required|numeric|min:0|max:99999999',
            'service_charge' => 'required|numeric|min:0|max:99999999',
            // Key feature
            'air_condition' => 'required|integer',
            'length' => 'required|numeric|min:1|max:9999999',
            'width' => 'required|numeric|min:1|max:9999999',
            'height' => 'required|numeric|min:1|max:9999999',
            'boot_space' => 'required|numeric|min:1|max:999999',
            'power_windows' => 'required|string',
            'fuel_tank_capacity' => 'required|numeric|min:1|max:999999',
            'seat_upholstery' => 'required|string',
            // Key specification
            'safety_ratings' => ['required', Rule::in([1,2,3,4,5])],
            'engine_capacity' => 'required|numeric|min:1|max:999999',
            'power' => 'required|numeric|min:1|max:99999',
            'torque' => 'required|numeric|min:1|max:99999',
            'drive_train' => 'required|string',
            'acceleration' => 'required|string',
            'top_speed' => 'required|string',
            'mileage' => 'required|numeric',
            'fuel_types.*' => ['required', Rule::in(array_keys(config('params.car.fuel_type')))],
            'transmission_types.*' => ['required', Rule::in(array_keys(config('params.car.transmission_type')))],
            'professions.*' => ['required', Rule::in(array_keys(config('params.professions')))],
            'colors.*' => ['required', Rule::in(array_keys(config('params.colors')))],
            // Base varient pricing
            'ex_showroom_price' => 'required|numeric|min:0|max:99999999',
            'on_road_price' => 'required|numeric|min:0|max:99999999',
            'finance_available' => 'required|numeric|min:0|max:99999999',
            'insurance' => 'required|numeric|min:0|max:99999999',
            'service_charge' => 'required|numeric|min:0|max:99999999',
            'image' => 'required|mimes:jpg,png,jpeg|max:2048',
            'image_.*' => 'mimes:jpg,png,jpeg|max:2048',
            // 'image_2' => 'mimes:jpg,png,jpeg|max:2048',
            // 'image_3' => 'mimes:jpg,png,jpeg|max:2048',
            // 'image_4' => 'mimes:jpg,png,jpeg|max:2048',
            'image_old_.*' => 'nullable|string',
            // 'image_old_2' => 'nullable|string',
            // 'image_old_3' => 'nullable|string',
            // 'image_old_4' => 'nullable|string',
            'is_removed_image' => 'nullable|string',
            'image_removed_.*' => 'nullable|string',
            // 'image_removed_2' => 'nullable|string',
            // 'image_removed_3' => 'nullable|string',
            // 'image_removed_4' => 'nullable|string',
            'deleted_image_id_.*' => 'nullable|string',
            // 'deleted_image_id_2' => 'nullable|string',
            // 'deleted_image_id_3' => 'nullable|string',
            // 'deleted_image_id_4' => 'nullable|string',
            //summary
            'why_choose' => 'nullable|string',
            'market_introduction' => 'nullable|string',
            'engine_transmission' => 'nullable|string',
            'exterior' => 'nullable|string',
            'interior' => 'nullable|string',
            'safety_features' => 'nullable|string',
            'rivals' => 'nullable|string',
            //price
            'base_ex_showroom_price' => 'required|numeric|min:0|max:99999999',
            'base_on_road_price' => 'required|numeric|min:0|max:99999999',
            'base_finance_available' => 'required|numeric|min:0|max:99999999',
            'base_insurance' => 'required|numeric|min:0|max:99999999',
            'base_service_charge' => 'required|numeric|min:0|max:99999999',
            'fuel_type' => [Rule::in(array_keys(config('params.car.fuel_type')))],
            'transmission_type' => [Rule::in(array_keys(config('params.car.transmission_type')))],
            'body_type_id' => [
                'required',
                Rule::exists(BodyType::class, 'id')->where(function ($query) {
                    return $query->where('status', BodyType::STATUS_ACTIVE);
                })
            ],
            'base_mileage' => 'required|numeric',
            'base_power' => 'required|numeric',
            'base_torque' => 'required|numeric',
            'base_fuel_tank_capacity' => 'required|numeric',
            'front_suspension' => 'required|numeric',
            'rear_suspension' => 'required|numeric',
            'steering_type' => 'required|numeric',
            'steering_column' => 'required|numeric',
            'tuning_radius' => 'required|numeric',
            'front_brake_type' => 'required|numeric',
            'rear_brake_type' => 'required|numeric',
            'alloy_wheel_front' => 'required|integer',
            'alloy_wheel_rear' => 'required|integer',
            'power_steering' => 'required|integer',
            'base_length' => 'required|numeric|min:1|max:9999999',
            'base_width' => 'required|numeric|min:1|max:9999999',
            'base_height' => 'required|numeric|min:1|max:9999999',
            'base_seat_upholstery' => 'required|string',
            'base_seat_capacity' => 'required|string',
            'base_air_condition' => 'required|integer',
            'wheel_covers' => 'required|integer',
            '360_view_camera' => 'required|integer',
            'base_boot_space' => 'required|numeric|min:1|max:9999999',
            'tachometer' => 'required|integer',
            'electronic_multi_tripmeter' => 'required|integer',
            'digital_odometer' => 'required|integer',
            'LED_Taillights' => 'required|integer',
            'automatic_headlamps' => 'required|integer',
            'LED_DRLs' => 'required|integer',
            'Halogen_Headlamps' => 'required|integer',
            'LED_Headlights' => 'required|integer',
            'base_engine_type' => 'required|numeric|min:1|max:9999999',
            'anti_theft_alarm' => 'required|integer',
            'no_of_airbags' => 'required|integer',
            'passenger_airbags' => 'required|integer',
            'driver_airbags' => 'required|integer',
            'child_safety_locks' => 'required|integer',
            'integrated_antenna' => 'required|integer',
            'apple_car_play' => 'required|integer',
            'touch_screen' => 'required|integer',
            'speakers_rear' => 'required|integer',
            'speakers_front' => 'required|integer',
            'radio' => 'required|integer',
            'android_auto' => 'required|integer',
            'digital_clock' => 'required|integer',
            'usb_charger' => 'required|integer',
            'bluetooth' => 'required|integer',
        ];
    }
     /**
     * @return array
     */
    private function updateRules()
    {
        return [
            'name' => ['required', new RegexAlphaNumSpaceHyphen, 'string', 'max:200'],
            'icon' => 'nullable|mimes:jpg,png,jpeg|max:2048',
            'is_top_brand' => ['required', Rule::in(array_keys(config('params.brand.is_top_brand')))],
            'status' => ['required', Rule::in(array_keys(config('params.brand.status')))],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'This field is required.',
        ];
    }
}