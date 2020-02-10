<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class AmountRule implements ImplicitRule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) {

        if (!is_float(minorToFloat($value))) {
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
        return 'The :attribute should be in minor units eg. 000000000100 for 1.00 .';
    }
}