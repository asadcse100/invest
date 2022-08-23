<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Gtmin implements Rule
{
    private $min;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($min)
    {
        $this->min = $min;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  mixed  $min
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($value != 0) {
            return $value > $this->min;
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The value must be greater than :minimum.', ['minimum' => $this->min]);
    }
}
