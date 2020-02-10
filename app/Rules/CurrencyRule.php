<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class CurrencyRule implements ImplicitRule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) {

        if (!in_array($value, config('currency'))) {
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
        return 'The :attribute is not a valid ISO 4217 code.';
    }
}