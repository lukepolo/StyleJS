<?php

namespace App\Rules;

use App\Models\Repository;
use Illuminate\Contracts\Validation\Rule;

class AnalysisSetting implements Rule
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
        return in_array($value, array_keys(Repository::ANALYSIS_SETTINGS));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You must select one of the following analysis settings '.implode(', ', Repository::ANALYSIS_SETTINGS).'.';
    }
}
