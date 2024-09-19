<?php

namespace App\Http\Requests\Admin;

use App\Models\Brand;
use App\Models\Car;
use App\Models\CarVersion;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RegexAlphaNumSpaceHyphen;

class FaqRequest extends FormRequest
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
     
        return [
            'brand_id' => [
                'required',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                })
            ],
            'car_id' => [
                'required',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                })
            ],
            'car_version_id' => [
                'nullable',
                Rule::exists(CarVersion::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                })
            ],
            'question' => ['required',  'string', 'max:500',],
            'answer' => ['required', 'string'],
            'sort_order' => ['required', 'integer'],
            'status' => ['required', Rule::in(array_keys(config('params.faq.status')))],
            // 'answer_status' => ['required', Rule::in(array_keys(config('params.faq.answer_status')))],
        ];
    }
     /**
     * @return array
     */
    private function updateRules()
    {
        return [
            'brand_id' => [
                'required',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                })
            ],
            'car_id' => [
                'required',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                })
            ],
            'car_version_id' => [
                'nullable',
                Rule::exists(CarVersion::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                })
            ],
            'question' => ['required',  'string', 'max:500',],
            'answer' => ['required', 'string'],
            'sort_order' => ['required', 'integer'],
            'status' => ['required', Rule::in(array_keys(config('params.faq.status')))],
            // 'answer_status' => ['required', Rule::in(array_keys(config('params.faq.answer_status')))],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'This field is required.',
        ];
    }
}