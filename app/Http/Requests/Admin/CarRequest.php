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
            'all_profession' => $this->all_profession ? 1 : 0,
            'all_travel' => $this->all_travel ? 1 : 0,
            'date_1' => date('Y-m-d', strtotime($this->date_1)),
            'date_2' => date('Y-m-d', strtotime($this->date_2)),
            'date_3' => date('Y-m-d', strtotime($this->date_3)),
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
            'all_fuels' => 'nullable',
            'all_transmissions' => 'nullable',
            'all_profession' => 'nullable',
            'all_travel' => 'nullable',

            'model_name' => 'required|string|max:100',
            'sort_order' => 'required|integer',
            'is_upcoming' => ['required', Rule::in([Car::LAUNCHED, Car::UPCOMING])],
            'is_just_launched' => ['required', Rule::in([Car::JUST_LAUNCHED, Car::NOT_JUST_LAUNCHED])],
            'just_launch_sort_order' => [
                'required_if:is_just_launched,' . Car::JUST_LAUNCHED,
                'nullable',
            ],
            'status' => ['required', Rule::in(array_keys(config('params.car.status')))],

            'ex_showroom_price' => 'required|numeric|min:0|max:99999999',
            'on_road_price' => 'nullable|numeric|min:0|max:99999999',
            'finance_available' => 'nullable|numeric|min:0|max:99999999',
            'insurance' => 'nullable|numeric|min:0|max:99999999',
            'service_charge' => 'nullable|numeric|min:0|max:99999999',

            // 'engine_type' => 'required|string',
            // 'no_of_cylinders' => 'nullable|integer',
            // 'valves_per_cylinder' => 'nullable|integer',
            // 'bore_stroke' => 'required|string',
            // 'compression_ratio' => 'required|string',
            // 'super_charge' => 'nullable|integer',
            // 'gear_box' => 'required|string',
            'engine_capacity' => 'nullable|numeric|min:1|max:999999',
            'power' => 'nullable|numeric|min:1|max:99999',
            'torque' => 'nullable|numeric|min:1|max:99999',
            'transmission_types.*' => ['nullable', Rule::in(array_keys(config('params.car.transmission_type')))],
            // 'drive_train' => 'required|string',
            // 'acceleration' => 'required|string',
            // 'top_speed' => 'required|string',
            'mileage' => 'nullable',
            // 'emission_norm_complains' => 'nullable|string',
            'fuel_tank_capacity' => 'nullable|numeric|min:1|max:999999',
            'fuel_types.*' => ['nullable', Rule::in(array_keys(config('params.car.fuel_type')))],
            'travel_type.*' => ['nullable', Rule::in(array_keys(config('params.car.travel_type')))],
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
            'seat_capacity' => 'nullable|integer',
            // 'air_condition' => 'nullable|integer',
            // 'wheel_covers' => 'nullable|integer',
            // 'alloy_wheels' => 'nullable|integer',
            // '360_view_camera' => 'nullable|integer',
            'view_camera' => ['required', Rule::in(array_keys(config('params.car.view-camera')))],
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
            'no_of_airbags' => 'nullable|integer',
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

            'image' => 'required|mimes:jpg,png,jpeg|max:2048',
            'image_detail' => 'required|mimes:jpg,png,jpeg|max:2048',
            'image_1' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_1' => 'required_with:image_1',
            'image_2' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_2' => 'required_with:image_2',
            'image_3' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_3' => 'required_with:image_3',
            'image_4' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_4' => 'required_with:image_4',
            'image_5' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_5' => 'required_with:image_5',
            'image_6' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_6' => 'required_with:image_6',
            'image_7' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_7' => 'required_with:image_7',
            'image_8' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_8' => 'required_with:image_8',
            'image_9' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_9' => 'required_with:image_9',
            'image_10' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_10' => 'required_with:image_10',
            'image_11' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_11' => 'required_with:image_11',
            'image_12' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_12' => 'required_with:image_12',
            'image_13' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_13' => 'required_with:image_13',
            'image_14' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_14' => 'required_with:image_14',
            'image_15' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_15' => 'required_with:image_15',
            'image_16' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_16' => 'required_with:image_16',
            'image_17' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_17' => 'required_with:image_17',
            'image_18' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_18' => 'required_with:image_18',
            'image_19' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_19' => 'required_with:image_19',
            'image_20' => 'mimes:jpg,png,jpeg|max:2048',
            'img_section_20' => 'required_with:image_20',

            'image_old_' => 'nullable|string',
            'image_old_1' => 'nullable|string',
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

            'image_removed_1' => 'nullable|string',
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
            'deleted_image_id_1' => 'nullable|string',
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

            'why_choose' => 'nullable|string',
            'market_introduction' => 'nullable|string',
            'engine_transmission' => 'nullable|string',
            'exterior' => 'nullable|string',
            'interior' => 'nullable|string',
            'safety_features' => 'nullable|string',
            'mileage_summary' => 'nullable|string',
            'rivals' => 'nullable|string',

            'professions.*' => ['nullable', Rule::in(array_keys(config('params.professions')))],
            'colors.*' => ['nullable',Rule::exists('brand_color_mappings', 'id')],
            // 'colors_image_1' => 'mimes:jpg,png,jpeg|max:2048',
            // 'colors_image_2' => 'mimes:jpg,png,jpeg|max:2048',
            // 'colors_image_3' => 'mimes:jpg,png,jpeg|max:2048',
            // 'colors_image_4' => 'mimes:jpg,png,jpeg|max:2048',
            // 'colors_image_5' => 'mimes:jpg,png,jpeg|max:2048',
            // 'colors_image_6' => 'mimes:jpg,png,jpeg|max:2048',
            // 'colors_image_7' => 'mimes:jpg,png,jpeg|max:2048',
            // 'colors_image_8' => 'mimes:jpg,png,jpeg|max:2048',
            // 'colors_image_9' => 'mimes:jpg,png,jpeg|max:2048',
            // 'colors_image_10' => 'mimes:jpg,png,jpeg|max:2048',
            // 'colors_image_11' => 'mimes:jpg,png,jpeg|max:2048',
            // 'colors_image_12' => 'mimes:jpg,png,jpeg|max:2048',
            // 'colors_image_13' => 'mimes:jpg,png,jpeg|max:2048',
            // 'colors_image_14' => 'mimes:jpg,png,jpeg|max:2048',
            // 'colors_image_15' => 'mimes:jpg,png,jpeg|max:2048',
            // 'colors_image_16' => 'mimes:jpg,png,jpeg|max:2048',
            // 'colors_image_17' => 'mimes:jpg,png,jpeg|max:2048',
            // 'colors_image_18' => 'mimes:jpg,png,jpeg|max:2048',


            'title_1' => 'nullable|string',
            'description_1' => 'nullable|string',
            'posted_media_1' => 'nullable|string|max:199',
            'date_1' => 'nullable|date',
            'thumbnail_1' => 'mimes:jpg,png,jpeg|max:2048',
            'video_1' => 'nullable|mimes:mp4|max:2048',

            'title_2' => 'nullable|string',
            'description_2' => 'nullable|string',
            'posted_media_2' => 'nullable|string|max:199',
            'date_2' => 'nullable|date',
            'thumbnail_2' => 'mimes:jpg,png,jpeg|max:2048',
            'video_2' => 'nullable|mimes:mp4|max:2048',

            'title_3' => 'nullable|string',
            'description_3' => 'nullable|string',
            'posted_media_3' => 'nullable|string|max:199',
            'date_3' => 'nullable|date',
            'thumbnail_3' => 'mimes:jpg,png,jpeg|max:2048',
            'video_3' => 'nullable|mimes:mp4|max:2048',

        ];
    }
     /**
     * @return array
     */
    private function updateRules()
    {
        return [
           // Basic Info
           'brand_id' => [
            'required',
            Rule::exists(Brand::class, 'id')->where(function ($query) {
                return $query->where('status', Brand::STATUS_ACTIVE);
            })
        ],
        'all_fuels' => 'nullable',
        'all_transmissions' => 'nullable',
        'all_profession' => 'nullable',
        'all_travel' => 'nullable',

        'model_name' => 'required|string|max:100',
        'sort_order' => 'required|integer',
        'is_upcoming' => ['required', Rule::in([Car::LAUNCHED, Car::UPCOMING])],
        'is_just_launched' => ['required', Rule::in([Car::JUST_LAUNCHED, Car::NOT_JUST_LAUNCHED])],
        'status' => ['required', Rule::in(array_keys(config('params.car.status')))],
        'just_launch_sort_order' => [
                function ($attribute, $value, $fail) {
                    if (request('is_just_launched') == Car::JUST_LAUNCHED && request('is_upcoming') == Car::LAUNCHED) {
                        if (is_null($value)) {
                            $fail('The ' . $attribute . ' field is required when the car is just launched and car is not upcoming');
                        }
                    }
                },
        ],
        'ex_showroom_price' => 'required|numeric|min:0|max:99999999',
        'on_road_price' => 'nullable|numeric|min:0|max:99999999',
        'finance_available' => 'nullable|numeric|min:0|max:99999999',
        'insurance' => 'nullable|numeric|min:0|max:99999999',
        'service_charge' => 'nullable|numeric|min:0|max:99999999',

        // 'engine_type' => 'required|string',
        // 'no_of_cylinders' => 'nullable|integer',
        // 'valves_per_cylinder' => 'nullable|integer',
        // 'bore_stroke' => 'required|string',
        // 'compression_ratio' => 'required|string',
        // 'super_charge' => 'nullable|integer',
        // 'gear_box' => 'required|string',
        'engine_capacity' => 'nullable|numeric|min:1|max:999999',
        'power' => 'nullable|numeric|min:1|max:99999',
        'torque' => 'nullable|numeric|min:1|max:99999',
        'transmission_types.*' => ['nullable', Rule::in(array_keys(config('params.car.transmission_type')))],
        // 'drive_train' => 'required|string',
        // 'acceleration' => 'required|string',
        // 'top_speed' => 'required|string',
        'mileage' => 'nullable',
       // 'emission_norm_complains' => 'nullable|string',
        'fuel_tank_capacity' => 'nullable|numeric|min:1|max:999999',
        'fuel_types.*' => ['nullable', Rule::in(array_keys(config('params.car.fuel_type')))],
        'travel_type.*' => ['nullable', Rule::in(array_keys(config('params.car.travel_type')))],
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
        'seat_capacity' => 'nullable|integer',
        // 'air_condition' => 'nullable|integer',
        // 'wheel_covers' => 'nullable|integer',
        // 'alloy_wheels' => 'nullable|integer',
        // '360_view_camera' => 'nullable|integer',
        'view_camera' => ['required', Rule::in(array_keys(config('params.car.view-camera')))],
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
        'no_of_airbags' => 'nullable|integer',
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

        'image' => 'nullable|mimes:jpg,png,jpeg|max:2048',
        'image_detail' => 'nullable|mimes:jpg,png,jpeg|max:2048',
        'image_0' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_0' => 'required_with:image_0',
        'image_1' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_1' => 'required_with:image_1',
        'image_2' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_2' => 'required_with:image_2',
        'image_3' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_3' => 'required_with:image_3',
        'image_4' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_4' => 'required_with:image_4',
        'image_5' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_5' => 'required_with:image_5',
        'image_6' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_6' => 'required_with:image_6',
        'image_7' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_7' => 'required_with:image_7',
        'image_8' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_8' => 'required_with:image_8',
        'image_9' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_9' => 'required_with:image_9',
        'image_10' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_10' => 'required_with:image_10',
        'image_11' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_11' => 'required_with:image_11',
        'image_12' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_12' => 'required_with:image_12',
        'image_13' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_13' => 'required_with:image_13',
        'image_14' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_14' => 'required_with:image_14',
        'image_15' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_15' => 'required_with:image_15',
        'image_16' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_16' => 'required_with:image_16',
        'image_17' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_17' => 'required_with:image_17',
        'image_18' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_18' => 'required_with:image_18',
        'image_19' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_19' => 'required_with:image_19',
        'image_20' => 'mimes:jpg,png,jpeg|max:2048',
        'img_section_20' => 'required_with:image_20',

        'image_old_' => 'nullable|string',
        'image_old_0' => 'nullable|string',
        'image_old_1' => 'nullable|string',
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

        'image_removed_1' => 'nullable|string',
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
        'deleted_image_id_1' => 'nullable|string',
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

        'why_choose' => 'nullable|string',
        'market_introduction' => 'nullable|string',
        'engine_transmission' => 'nullable|string',
        'exterior' => 'nullable|string',
        'interior' => 'nullable|string',
        'safety_features' => 'nullable|string',
        'rivals' => 'nullable|string',
        'mileage_summary' => 'nullable|string',

        'professions.*' => ['nullable', Rule::in(array_keys(config('params.professions')))],
        'colors.*' => ['nullable',Rule::exists('brand_color_mappings', 'id')],
        // 'colors_image.*' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_1' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_2' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_3' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_4' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_5' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_6' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_7' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_8' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_9' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_10' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_11' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_12' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_13' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_14' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_15' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_16' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_17' => 'mimes:jpg,png,jpeg|max:2048',
        // 'colors_image_18' => 'mimes:jpg,png,jpeg|max:2048',


        'title_1' => 'nullable|string',
        'description_1' => 'nullable|string',
        'posted_media_1' => 'nullable|string|max:255',
        'date_1' => 'nullable|date',
        'thumbnail_1' => 'mimes:jpg,png,jpeg|max:2048',
        'video_1' => 'nullable|mimes:mp4|max:2048',

        'title_2' => 'nullable|string',
        'description_2' => 'nullable|string',
        'posted_media_2' => 'nullable|string|max:255',
        'date_2' => 'nullable|date',
        'thumbnail_2' => 'mimes:jpg,png,jpeg|max:2048',
        'video_2' => 'nullable|mimes:mp4|max:2048',

        'title_0' => 'nullable|string',
        'description_0' => 'nullable|string',
        'posted_media_0' => 'nullable|string|max:255',
        'date_0' => 'nullable|date',
        'thumbnail_0' => 'mimes:jpg,png,jpeg|max:2048',
        'video_0' => 'nullable|mimes:mp4|max:2048',

        'title_3' => 'nullable|string',
        'description_3' => 'nullable|string',
        'posted_media_3' => 'nullable|string|max:255',
        'date_3' => 'nullable|date',
        'thumbnail_3' => 'mimes:jpg,png,jpeg|max:2048',
        'video_3' => 'nullable|mimes:mp4|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'This field is required.',
            'max' => 'The text may not be greater than :max characters.',
        ];
    }


     /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // if (!isset($this->colors)) {
            //     $validator->errors()->add('colors', 'Please choose a color.');
            // }

            if (!$this->all_fuels && !isset($this->fuel_types)) {
                $validator->errors()->add('fuel_types', 'Please choose a fuel type.');
            }

            if (!$this->all_travel && !isset($this->travel_type)) {
                $validator->errors()->add('travel_type', 'Please choose a travel type.');
            }

            if (!$this->all_transmissions && !isset($this->transmission_types)) {
                $validator->errors()->add('transmission_types', 'Please choose a transmission type.');
            }

        });
    }
}
