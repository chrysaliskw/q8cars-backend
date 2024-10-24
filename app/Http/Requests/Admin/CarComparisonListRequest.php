<?php

namespace App\Http\Requests\Admin;

use App\Models\Brand;
use App\Models\Car;
use App\Models\CarVersion;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CarComparisonListRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

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
            'body_type_id' => 'required',
            'body_type_id_text' => 'required',
            'car_id' => [
                'nullable',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                }),
            ],
            'car_id_1' => [
                'required',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                }),
            ],
            'car_id_2' => [
                'required',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                }),
            ],
            'car_version_1_id' => [
                'nullable',
                Rule::exists(CarVersion::class, 'id')->where(function ($query) {
                    return $query->where('status', CarVersion::STATUS_ACTIVE);
                }),
            ],
            'car_2_version_id' => [
                'nullable',
                Rule::exists(CarVersion::class, 'id')->where(function ($query) {
                    return $query->where('status', CarVersion::STATUS_ACTIVE);
                }),
            ],
            'brand_id' => [
                'nullable',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                }),
            ],
            'brand_1_id' => [
                'required',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                }),
            ],
            'brand_2_id' => [
                'required',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                }),
            ],
            'page' => ['required', 'integer'],
        ];
    }

    private function updateRules()
    {
        return [
            'body_type_id' => 'required',
            // 'body_type_id_text' => 'required',
            'car_id' => [
                'nullable',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                }),
            ],
            'car_id_1' => [
                'required',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                }),
            ],
            'car_id_2' => [
                'required',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                }),
            ],
            'car_version_1_id' => [
                'nullable',
                Rule::exists(CarVersion::class, 'id')->where(function ($query) {
                    return $query->where('status', CarVersion::STATUS_ACTIVE);
                }),
            ],
            'car_2_version_id' => [
                'nullable',
                Rule::exists(CarVersion::class, 'id')->where(function ($query) {
                    return $query->where('status', CarVersion::STATUS_ACTIVE);
                }),
            ],
            'brand_id' => [
                'nullable',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                }),
            ],
            'brand_1_id' => [
                'required',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                }),
            ],
            'brand_2_id' => [
                'required',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                }),
            ],
            'page' => ['required', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'This field is required.',
            'car_id.exists' => 'The selected car ID is invalid.',
            // 'car_id.prohibited' => 'The car ID field is prohibited in Home page and Comparison page.',
            'brand_id.exists' => 'The selected brand ID is invalid.',
            // 'brand_id.prohibited' => 'The brand ID field is prohibited in Home page and Comparison page.',
        ];
    }
    

 

    public function withValidator($validator)
    {

        $validator->sometimes('car_id', 'required', function ($input) {
            return $input->page == 2;
        });
    
        $validator->sometimes('brand_id', 'required', function ($input) {
            return $input->page == 2;
        });

        $validator->after(function ($validator) {
            if ($this->page != 2) {
                $this->merge([
                    'car_id' => null,
                    'brand_id' => null,
                ]);
            }
        });
    }
}