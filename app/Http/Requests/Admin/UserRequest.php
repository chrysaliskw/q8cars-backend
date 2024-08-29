<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Models\Country;
use App\Models\Edition;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use UserEditionMap;

class UserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('post')) {
            return $this->createRules();
        }

        return $this->updateRules();
    }

    /**
     * @return array
     */
    private function createRules()
    {
        return [
            'name' => ['required', 'regex:/^[a-zA-Z\s\d]*$/', 'string', 'max:255', ],
            'status' => ['required', Rule::in(array_keys(config('params.user.status')))],
            'email' => 'required|email:filter|string|max:255|unique:users',
            'phone_code' => 'required|regex:/^\+?[\d]{1,5}$/',
            'mobile' => [
                'required', 'regex:/^[0-9]*$/', 'max:8', 'min:7',
                Rule::unique('users')->whereNull('deleted_at'),
            ],
            'address' => 'nullable|string|max:255',
            'picture' => 'nullable||mimes:jpg,png,jpeg|max:2048',
        ];
    }
    
    /**
     * @return array
     */
    private function updateRules()
    {
        $user = $this->route('user');
        return [
            'name' => ['required', 'regex:/^[a-zA-Z\s\d]*$/', 'string', 'max:255', ],
            'status' => ['required', Rule::in(array_keys(config('params.user.status')))],
            'email' => [
                'required', 'email:filter', 'string', 'max:255',
                Rule::unique('users')->ignore($user->id)->whereNull('deleted_at'),
            ],
            'phone_code' => 'required|regex:/^\+?[\d]{1,5}$/',
            'mobile' => [
                'required', 'regex:/^[0-9]*$/', 'max:8', 'min:7',
                Rule::unique('users')->ignore($user->id)->whereNull('deleted_at'),
            ],
            'address' => 'nullable|string|max:255',
            'picture' => 'nullable||mimes:jpg,png,jpeg|max:2048',
        ];    
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
            'mobile.min' => 'The mobile must be at least 7 digits.',
            'mobile.max' => 'The mobile may not be greater than 12 digits.',
        
        ];
    }
}
