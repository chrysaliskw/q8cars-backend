<?php

namespace App\Http\Requests\Admin;

use App\Models\Brand;
use App\Models\Car;
use App\Models\CarVersion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarComparisonListRequest extends FormRequest
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
    //  dd($this->all());
        return [
            
            'car_id' => [
                'nullable',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                })
            ],

            'car_id_1' => [
                'required',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);

                })
            ],

            'car_id_2' => [
                'required',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);

                })
            ],
            'car_version_1_id' => [
                'nullable',
                Rule::exists(CarVersion::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                    
                })
            ],

            'car_2_version_id' => [
                'nullable',
                Rule::exists(CarVersion::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                    
                })
            ],
            'brand_id' => [
                'nullable',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                })
            ],

            'brand_1_id' => [
                'required',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                })
            ],

            'brand_2_id' => [
                'required',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                })
            ],

            'page' => ['required', 'integer'],

            
            
        ];
    }
     /**
     * @return array
     */
    private function updateRules()

    
    {
        // dd($this->all());
        
        return [
            'car_id' => [
                'nullable',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                })
            ],

            'car_id_1' => [
                'required',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);

                })
            ],

            'car_id_2' => [
                'required',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);

                })
            ],
            'car_version_1_id' => [
                'nullable',
                Rule::exists(CarVersion::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                    
                })
            ],

            'car_2_version_id' => [
                'nullable',
                Rule::exists(CarVersion::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                    
                })
            ],
            'brand_id' => [
                'nullable',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                })
            ],

            'brand_1_id' => [
                'required',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                })
            ],

            'brand_2_id' => [
                'required',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                })
            ],

            'page' => ['required', 'integer'],

            
            
        ];
        
    }

    public function messages()
    {
        return [
            'required' => 'This field is required.',
            'car_id.exists' => 'Main car comparison list already exists!',

        ];
    }
}
