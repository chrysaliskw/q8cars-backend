<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class FileOrFileName implements Rule
{
    /**
     * @var string
     */
    private $msg;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (request()->hasFile($attribute) && $value instanceof UploadedFile)
        {
            if ($value->getSize() > 3000000) {
                $this->msg = 'The :attribute may not be greater than 2MB.';
                return false;
            }

            if (in_array($value->extension(), ['png', 'jpeg', 'jpg']) == false) {
                $this->msg = 'The :attribute must be a file of png, jpeg, jpg.';
                return false;
            }

            return true;
        }
        
        if (is_string($value) == false) {
            $this->msg = 'The :attribute is not a valid file.';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->msg;
    }
}
