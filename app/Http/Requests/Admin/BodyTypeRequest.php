<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RegexAlphaNumSpace;

class BodyTypeRequest extends FormRequest
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
            'name' => ['required', new RegexAlphaNumSpace, 'string', 'max:200','unique:body_types'],
            'icon' => 'required|mimes:jpg,png,jpeg|max:2048',
            'status' => ['required', Rule::in(array_keys(config('params.brand.status')))],
        ];
    }
     /**
     * @return array
     */
    private function updateRules()
    {
      
        $bodyType = $this->route('body_type'); 
        return [
           'name' => [
                'required',
                'string', 
                'max:200',
                new RegexAlphaNumSpace,
                Rule::unique('body_types')
                    ->ignore($bodyType->id) 
                    ->whereNull('deleted_at') 
            ],
            'icon' => 'nullable|mimes:jpg,png,jpeg|max:2048',
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