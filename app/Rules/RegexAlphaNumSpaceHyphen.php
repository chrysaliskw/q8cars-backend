<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RegexAlphaNumSpaceHyphen implements Rule
{
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
        // Update the regex pattern to include hyphens
        return preg_match("/^[a-zA-Z\s\d-]*$/", $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Only alphabets, numbers, spaces, and hyphens are allowed.';
    }
}
