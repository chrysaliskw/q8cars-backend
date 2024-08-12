<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RegexAlphaNumSpace;

class BrandRequest extends FormRequest
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
            'name' => ['required', new RegexAlphaNumSpace, 'string', 'max:200'],
            'icon' => 'required|mimes:jpg,png,jpeg|max:2048',
            'is_top_brand' => ['required', Rule::in(array_keys(config('params.brand.is_top_brand')))],
            'status' => ['required', Rule::in(array_keys(config('params.brand.status')))],
        ];
    }
     /**
     * @return array
     */
    private function updateRules()
    {
        return [
            'name' => ['required', new RegexAlphaNumSpace, 'string', 'max:200'],
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