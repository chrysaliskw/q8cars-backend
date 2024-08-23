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
            'all_transmissions' => $this->all_transmissions ? 1 : 0,
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
                'nullable',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                })
            ],
            'model_name' => 'required|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_just_launched' => ['nullable', Rule::in([Car::JUST_LAUNCHED, Car::NOT_JUST_LAUNCHED])],
            'status' => ['nullable', Rule::in(array_keys(config('params.car.status')))],
           
            'ex_showroom_price' => 'required|numeric|min:0|max:99999999',
            'on_road_price' => 'required|numeric|min:0|max:99999999',
            'finance_available' => 'required|numeric|min:0|max:99999999',
            'insurance' => 'required|numeric|min:0|max:99999999',
            'service_charge' => 'required|numeric|min:0|max:99999999',
           
            'engine_type' => 'nullable|string',
            'no_of_cylinders' => 'nullable|integer',
            'valves_per_cylinder' => 'nullable|integer',
            'bore_stroke' => 'nullable|string',
            'compression_ratio' => 'nullable|string',
            'super_charge' => 'nullable|integer',
            'engine_capacity' => 'required|numeric|min:1|max:999999',
            'power' => 'required|numeric|min:1|max:99999',
            'torque' => 'required|numeric|min:1|max:99999',
            'transmission_types.*' => ['nullable', Rule::in(array_keys(config('params.car.transmission_type')))],
            'drive_train' => 'required|string',
            'acceleration' => 'required|string',
            'top_speed' => 'required|string',
            'mileage' => 'required|numeric',
            'emission_norm_complains' => 'nullable|string',
            'fuel_tank_capacity' => 'required|numeric|min:1|max:999999',
            'fuel_types.*' => ['nullable', Rule::in(array_keys(config('params.car.fuel_type')))],
            'front_suspension' => 'nullable|string',
            'rear_suspension' => 'nullable|string',
            'steering_type' => 'nullable|string',
            'steering_column' => 'nullable|string',
            'tuning_radius' => 'nullable|numeric',
            'front_brake_type' => 'nullable|string',
            'rear_brake_type' => 'nullable|string',
            'alloy_wheel_front' => 'nullable|integer',
            'alloy_wheel_rear' => 'nullable|integer',
            'power_steering' => 'nullable|integer',
            'body_type_id' => [
                'required',
                Rule::exists(BodyType::class, 'id')->where(function ($query) {
                    return $query->where('status', BodyType::STATUS_ACTIVE);
                })
            ],
            'length' => 'required|numeric|min:1|max:9999999',
            'width' => 'required|numeric|min:1|max:9999999',
            'height' => 'required|numeric|min:1|max:9999999',
            'seat_upholstery' => 'required|string',
            'seat_capacity' => 'required|integer',
            'air_condition' => 'nullable|integer',
            'wheel_covers' => 'nullable|integer',
            '360_view_camera' => 'nullable|integer',
            'boot_space' => 'required|numeric|min:1|max:999999',
            'power_windows' => 'required|string',
            
            'tachometer' => 'nullable|integer',
            'electronic_multi_tripmeter' => 'nullable|integer',
            'digital_odometer' => 'nullable|integer',
            'LED_Taillights' => 'nullable|integer',
            'automatic_headlamps' => 'nullable|integer',
            'LED_DRLs' => 'nullable|integer',
            'Halogen_Headlamps' => 'nullable|integer',
            'LED_Headlights' => 'nullable|integer',
            'safety_ratings' => ['required', Rule::in([1,2,3,4,5])],
            'anti_theft_alarm' => 'nullable|integer',
            'no_of_airbags' => 'nullable|integer',
            'passenger_airbags' => 'nullable|integer',
            'driver_airbags' => 'nullable|integer',
            'child_safety_locks' => 'nullable|integer',
            'integrated_antenna' => 'nullable|integer',
            'apple_car_play' => 'nullable|integer',
            'touch_screen' => 'nullable|integer',
            'speakers_rear' => 'nullable|integer',
            'speakers_front' => 'nullable|integer',
            'radio' => 'nullable|integer',
            'android_auto' => 'nullable|integer',
            'digital_clock' => 'nullable|integer',
            'usb_charger' => 'nullable|integer',
            'bluetooth' => 'nullable|integer',
           
            'image' => 'required|mimes:jpg,png,jpeg|max:2048',
            'image_2' => 'mimes:jpg,png,jpeg|max:2048',
            'image_3' => 'mimes:jpg,png,jpeg|max:2048',
            'image_4' => 'mimes:jpg,png,jpeg|max:2048',
            'image_5' => 'mimes:jpg,png,jpeg|max:2048',
            'image_6' => 'mimes:jpg,png,jpeg|max:2048',
            'image_7' => 'mimes:jpg,png,jpeg|max:2048',
            'image_8' => 'mimes:jpg,png,jpeg|max:2048',
            'image_9' => 'mimes:jpg,png,jpeg|max:2048',
            'image_10' => 'mimes:jpg,png,jpeg|max:2048',
            'image_11' => 'mimes:jpg,png,jpeg|max:2048',
            'image_12' => 'mimes:jpg,png,jpeg|max:2048',
            'image_13' => 'mimes:jpg,png,jpeg|max:2048',
            'image_14' => 'mimes:jpg,png,jpeg|max:2048',
            'image_15' => 'mimes:jpg,png,jpeg|max:2048',
            'image_16' => 'mimes:jpg,png,jpeg|max:2048',
            'image_17' => 'mimes:jpg,png,jpeg|max:2048',
            'image_18' => 'mimes:jpg,png,jpeg|max:2048',
            'image_19' => 'mimes:jpg,png,jpeg|max:2048',
            'image_20' => 'mimes:jpg,png,jpeg|max:2048',

            'image_old_' => 'nullable|string',
            'image_old_2' => 'nullable|string',
            'image_old_3' => 'nullable|string',
            'image_old_4' => 'nullable|string',
            'image_old_5' => 'nullable|string',
            'image_old_6' => 'nullable|string',
            'image_old_7' => 'nullable|string',
            'image_old_8' => 'nullable|string',
            'image_old_9' => 'nullable|string',
            'image_old_10' => 'nullable|string',
            'image_old_11' => 'nullable|string',
            'image_old_12' => 'nullable|string',
            'image_old_13' => 'nullable|string',
            'image_old_14' => 'nullable|string',
            'image_old_15' => 'nullable|string',
            'image_old_16' => 'nullable|string',
            'image_old_17' => 'nullable|string',
            'image_old_18' => 'nullable|string',
            'image_old_19' => 'nullable|string',
            'image_old_20' => 'nullable|string',

            'is_removed_image' => 'nullable|string',
         
            'image_removed_2' => 'nullable|string',
            'image_removed_3' => 'nullable|string',
            'image_removed_4' => 'nullable|string',
            'image_removed_5' => 'nullable|string',
            'image_removed_6' => 'nullable|string',
            'image_removed_7' => 'nullable|string',
            'image_removed_8' => 'nullable|string',
            'image_removed_9' => 'nullable|string',
            'image_removed_10' => 'nullable|string',
            'image_removed_11' => 'nullable|string',
            'image_removed_12' => 'nullable|string',
            'image_removed_13' => 'nullable|string',
            'image_removed_14' => 'nullable|string',
            'image_removed_15' => 'nullable|string',
            'image_removed_16' => 'nullable|string',
            'image_removed_17' => 'nullable|string',
            'image_removed_18' => 'nullable|string',
            'image_removed_19' => 'nullable|string',
            'image_removed_20' => 'nullable|string',

            'deleted_image_id' => 'nullable|string',
            'deleted_image_id_2' => 'nullable|string',
            'deleted_image_id_3' => 'nullable|string',
            'deleted_image_id_4' => 'nullable|string',
            'deleted_image_id_5' => 'nullable|string',
            'deleted_image_id_6' => 'nullable|string',
            'deleted_image_id_7' => 'nullable|string',
            'deleted_image_id_8' => 'nullable|string',
            'deleted_image_id_9' => 'nullable|string',
            'deleted_image_id_10' => 'nullable|string',
            'deleted_image_id_11' => 'nullable|string',
            'deleted_image_id_12' => 'nullable|string',
            'deleted_image_id_13' => 'nullable|string',
            'deleted_image_id_14' => 'nullable|string',
            'deleted_image_id_15' => 'nullable|string',
            'deleted_image_id_16' => 'nullable|string',  
            'deleted_image_id_17' => 'nullable|string',
            'deleted_image_id_18' => 'nullable|string',
            'deleted_image_id_19' => 'nullable|string',
            'deleted_image_id_20' => 'nullable|string',
        
            'why_choose' => 'required|string',
            'market_introduction' => 'required|string',
            'engine_transmission' => 'required|string',
            'exterior' => 'required|string',
            'interior' => 'required|string',
            'safety_features' => 'required|string',
            'rivals' => 'nullable|string',

            'professions.*' => ['nullable', Rule::in(array_keys(config('params.professions')))],
            'colors.*' => ['required', Rule::in(array_keys(config('params.colors')))],
        
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