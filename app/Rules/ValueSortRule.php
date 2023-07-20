<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValueSortRule implements Rule
{
    protected $value = ['desc', 'asc'];

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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        if ($value) {
            if (!in_array(strtolower($value), $this->value)) {
                return false;
            }
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
        return trans('api.request.column.value.nonexistent');
    }
}
