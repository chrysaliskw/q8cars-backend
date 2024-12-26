<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'car_id' => 'required|exists:cars,id',
            'car_version_id' => 'nullable|exists:car_versions,id',
            'key_feature_1' => 'nullable|string',
            'key_feature_2' => 'nullable|string',
            'key_icon_1' => 'nullable|image|max:2048|dimensions:width=34,height=35|mimes:png',
            'key_icon_2' => 'nullable|image|max:2048|dimensions:width=34,height=35|mimes:png',
            'description' => 'required|string',
            'offer' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => ['required', Rule::in(array_keys(config('params.offers.status')))],
            'show_in_suggestions' => 'nullable|boolean',
        ];
    }

    /**
     * Configure the validator instance.
     */
    protected function withValidator($validator): void
    {
        if ($this->isMethod('post')) {
            $validator->after(function ($validator) {
                // Check if key_feature_1 and key_icon_1 are dependent
                if ($this->filled('key_feature_1') && !$this->hasFile('key_icon_1')) {
                    $validator->errors()->add('key_icon_1', 'The key icon 1 is required when key feature 1 is present.');
                }
                if (!$this->filled('key_feature_1') && $this->hasFile('key_icon_1')) {
                    $validator->errors()->add('key_feature_1', 'The key feature 1 is required when key icon 1 is present.');
                }
    
                // Check if key_feature_2 and key_icon_2 are dependent
                if ($this->filled('key_feature_2') && !$this->hasFile('key_icon_2')) {
                    $validator->errors()->add('key_icon_2', 'The key icon 2 is required when key feature 2 is present.');
                }
                if (!$this->filled('key_feature_2') && $this->hasFile('key_icon_2')) {
                    $validator->errors()->add('key_feature_2', 'The key feature 2 is required when key icon 2 is present.');
                }
            });
      
        }
      
    }
}
