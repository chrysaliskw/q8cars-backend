<?php

namespace App\Http\Requests\Admin;

use App\Models\Bank;
use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
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
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'base_gross_income' => 'nullable|numeric|min:0|max:99999999',
            'base_other_emi' => 'nullable|numeric|min:0|max:99999999',
            'base_interest_rate' => 'nullable|numeric|min:0|max:99999999',
            'logo' => 'nullable|image|max:2048|mimes:png,jpg,jpeg',
            'status' => 'required|in:' . implode(',', array_keys(Bank::STATUSES)),
        ];
    }
}
