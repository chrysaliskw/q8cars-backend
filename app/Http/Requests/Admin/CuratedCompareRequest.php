<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use App\Models\Brand;
use App\Models\Car;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Exceptions\HttpResponseException;

class CuratedCompareRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return $this->createRules();
        }

        return $this->updateRules();
    }

    private function createRules()
    {
        // dd($this->all());
        return [
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
            'brand_3_id' => [
                'required',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                })
            ],
            'car_1_id' => [
                'required',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                })
            ],
            'car_2_id' => [
                'required',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                })
            ],
            'car_3_id' => [
                'required',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                })
            ],
            'image_1' => [
                'required',
                // 'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048'
            ],
            'image_2' => [
                'required',
                // 'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048'
            ],
            'image_3' => [
                'required',
                // 'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048'
            ],

            'source' => ['required', 'string'],
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
            'status' => ['required', Rule::in(array_keys(config('params.curated-comparisons.status')))],
            'published_date' => ['required', 'date'],

        ];
    }

    private function updateRules()
    {
        return [
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
            'brand_3_id' => [
                'required',
                Rule::exists(Brand::class, 'id')->where(function ($query) {
                    return $query->where('status', Brand::STATUS_ACTIVE);
                })
            ],
            'car_1_id' => [
                'required',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                })
            ],
            'car_2_id' => [
                'required',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                })
            ],
            'car_3_id' => [
                'required',
                Rule::exists(Car::class, 'id')->where(function ($query) {
                    return $query->where('status', Car::STATUS_ACTIVE);
                })
            ],
            'image_1' => [
                'nullable',
                'file', // Ensures that the input is a file
                'mimes:jpeg,png,jpg,gif', // Specifies allowed formats
                'max:2048', // Limits file size to 2 MB
            ],

            'image_2' => [
                'nullable',

                'mimes:jpeg,png,jpg,gif',
                'max:2048'
            ],
            'image_3' => [
                'nullable',

                'mimes:jpeg,png,jpg,gif',
                'max:2048'
            ],

            'source' => ['required', 'string'],
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
            'status' => ['required', Rule::in(array_keys(config('params.curated-comparisons.status')))],
            'published_date' => ['required', 'date'],

        ];
    }




    public function messages()
    {
        return [
            'required' => 'This field is required.',
            'max' => 'The image may not be greater than :max kilobytes.',
            'mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
        ];
    }
}
