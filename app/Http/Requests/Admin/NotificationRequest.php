<?php

namespace App\Http\Requests\Admin;

use App\Models\Notification;
use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return $this->createRules();
        }

        return $this->updateRules();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function createRules(): array
    {
        // dd($this->all());
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'required|image|max:2048|mimes:png,jpg,jpeg',
            'logo' => 'required|image|max:2048|mimes:png,jpg,jpeg',
            'business_name' => 'required|string|max:255',
            'end_date' => 'required|date|after_or_equal:start_date',
            // 'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:' . implode(',', array_keys(Notification::STATUSES)),
        ];
    }

    public function updateRules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048|mimes:png,jpg,jpeg',
            'logo' => 'nullable|image|max:2048|mimes:png,jpg,jpeg',
            'business_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:' . implode(',', array_keys(Notification::STATUSES)),
        ];
    }
    // public function rules(): array
    // {
    //     return [
    //         'title' => 'required|string|max:255',
    //         'description' => 'required|string|max:255',
    //         'image' => 'required|image|max:2048|mimes:png,jpg,jpeg',
    //         'logo' => 'required|image|max:2048|mimes:png,jpg,jpeg',
    //         'business_name' => 'required|string|max:255',
    //         'start_date' => 'required|date',
    //         'end_date' => 'required|date|after_or_equal:start_date',
    //         'status' => 'required|in:' . implode(',', array_keys(Notification::STATUSES)),
    //     ];
    // }
}
