<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RegexAlphaNumSpaceHyphen;

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
            'name' => ['required', new RegexAlphaNumSpaceHyphen, 'string', 'max:200','unique:brands'],
            'icon' => 'required|mimes:jpg,png,jpeg|max:2048',
            'is_top_brand' => ['required', Rule::in(array_keys(config('params.brand.is_top_brand')))],
            'is_recently_purchased' => ['required', Rule::in(array_keys(config('params.brand.is_top_brand')))],
            'status' => ['required', Rule::in(array_keys(config('params.brand.status')))],
        ];
    }
     /**
     * @return array
     */
    private function updateRules()
    {
        $brand = $this->route('brand');
        return [
            'name' => [
                'required',
                'string', 
                'max:200',
                new RegexAlphaNumSpaceHyphen,
                Rule::unique('brands')
                    ->ignore($brand->id) 
                    ->whereNull('deleted_at') 
            ],
            'icon' => 'nullable|mimes:jpg,png,jpeg|max:2048',
            'is_top_brand' => ['required', Rule::in(array_keys(config('params.brand.is_top_brand')))],
            'is_recently_purchased' => ['required', Rule::in(array_keys(config('params.brand.is_top_brand')))],
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