<?php

namespace App\Http\Requests\Admin;

use App\Models\Banner;
use Illuminate\Validation\Rule;
use App\Rules\RegexAlphaNumSpace;
use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'expiry_date' => date('Y-m-d', strtotime($this->expiry_date)),
            'start_date' => date('Y-m-d', strtotime($this->start_date))
        ]);
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
            'name' => ['required', 'string', 'max:200'],
            'file_name' => 'required|mimes:jpg,png,jpeg|max:2048|dimensions:min_width=800',   //1440, 720
            'file_name_mobile_view' => 'required|mimes:jpg,png,jpeg|max:2048|dimensions:min_width=250',   //1440, 720
            // 'file_name' => 'required|mimes:jpg,png,jpeg|max:2048|dimensions:min_width=1400,height=700',   //1440, 720
            // 'file_name_mobile_view' => 'required|mimes:jpg,png,jpeg|max:2048|dimensions:width=375,height=385',   //1440, 720
            // 'page' => ['nullable', Rule::in(array_keys(config('params.banners.page')))],
            'sort_order' => 'required|integer|min:1',
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
                'max:200'
            ],
              'file_name' => 'nullable|mimes:jpg,png,jpeg|max:2048|dimensions:min_width=800',   //1440, 720
            'file_name_mobile_view' => 'nullable|mimes:jpg,png,jpeg|max:2048|dimensions:min_width=250',   //1440, 720
            // 'file_name' => 'nullable|mimes:jpg,png,jpeg|max:2048|dimensions:width=1400,height=700',
            // 'file_name_mobile_view' => 'nullable|mimes:jpg,png,jpeg|max:2048|dimensions:width=375,height=385',   //1440, 720
            // 'page' => ['required', Rule::in(array_keys(config('params.banners.page')))],
            'sort_order' => 'required|integer|min:1',
            'status' => ['required', Rule::in(array_keys(config('params.brand.status')))],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'This field is required.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->ifNumOfHomePageBannerExceedsMaxLimit()) {
                $validator->errors()->add('page', 'Only maximum of 10 active banner is allowed in page home.');
            }
        });
    }

    /**
     * @return bool
     */
    private function ifNumOfHomePageBannerExceedsMaxLimit()
    {
        $count = Banner::where('page', Banner::PAGE_HOME)
                    ->active()
                    ->count();

        //on create
        if ($this->isMethod('post') && $count >= Banner::MAX_NUM_OF_PAGE_HOME) {
            return true;
        }
        //on update
        else if ($count > Banner::MAX_NUM_OF_PAGE_HOME) {
            return true;
        }

        return false;
    }
}