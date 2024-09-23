<?php

namespace App\Http\Requests\Admin;

use App\Models\Car;
use App\Models\Brand;
use App\Models\CarVersion;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class NewsPostRequest extends FormRequest
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
    private function createRules()
    {

        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => 'required|string',
            'image' => 'required|mimes:jpg,png,jpeg|max:2048',
            'media_logo' => 'required|mimes:jpg,png,jpeg|max:2048',
            'media_name' => ['required','string', 'max:255'],
            // 'youtube_video_link' => [function ($attr, $val, $fail) {
            //     if (! empty($val) && ! $this->validateUrl($val)) {
            //         $fail("The video link field is not a valid youtube link");
            //     }
            // }],
            // 'sort_order' => 'nullable|integer',
            'status' => ['required', Rule::in(array_keys(config('params.news.status')))],
            'read_time' => ['required', 'min:1', 'max:100', 'integer'],
            // 'notification'=>['nullable'],
            'is_trending'=>['nullable'],
            'expiry_date' => 'required|date|after_or_equal:today',
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
        ];
    }

    private function updateRules()
    {

        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => 'required|string',
            'image' => 'nullable|mimes:jpg,png,jpeg|max:2048',
            'media_logo' => 'nullable|mimes:jpg,png,jpeg|max:2048',
            'media_name' => ['required','string', 'max:255'],
            // 'youtube_video_link' => [function ($attr, $val, $fail) {
            //     if (! empty($val) && ! $this->validateUrl($val)) {
            //         $fail("The video link field is not a valid youtube link");
            //     }
            // }],
            // 'sort_order' => 'nullable|integer',
            'status' => ['required', Rule::in(array_keys(config('params.news.status')))],
            'read_time' => ['required', 'min:1', 'max:100', 'integer'],
            // 'notification'=>['nullable'],
            'is_trending'=>['nullable'],
            'expiry_date' => 'required|date|after_or_equal:today',
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
            //
           
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'dimensions' => 'The image must have min width: 100 and min height: 200',
           
        ];
    }

    public function validateUrl($url)
    {
        $url_parsed_arr = parse_url($url);
        if(array_key_exists('host', $url_parsed_arr)) {
            if ($url_parsed_arr['host'] == "www.youtube.com" && $url_parsed_arr['path'] == "/watch" && substr($url_parsed_arr['query'], 0, 2) == "v=" && substr($url_parsed_arr['query'], 2) != "") {
                return 1;
             } 
             else {
                 return 0;
             }
        }
        else {
            return 0;
        }
      
    }
    
}
