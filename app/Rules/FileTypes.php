<?php

namespace App\Rules;

use App\Models\Repository;
use Illuminate\Contracts\Validation\Rule;

class FileTypes implements Rule
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
        return count(array_intersect($value, Repository::FIlE_TYPES)) == count($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You can only use '.implode(', ', Repository::FIlE_TYPES).' file types.';
    }
}
