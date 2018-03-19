<?php

namespace App\Rules;

use App\Models\Repository;
use Illuminate\Contracts\Validation\Rule;

class PrettierProseWrap implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, Repository::PROSE_WRAP);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You must use one of the options '.implode(', ', Repository::PROSE_WRAP).'.';
    }
}
