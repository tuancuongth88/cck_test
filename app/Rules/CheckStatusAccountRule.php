<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class CheckStatusAccountRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $id;
    private $model;

    public function __construct($id, $model)
    {
        $this->id = $id;
        $this->model = new $model();
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
        $status = $this->model->find($this->id)->status;
        if($status == EXAMINATION_PENDING && ($value == CONFIRM || $value == REJECT)) {
            return true;
        }
        if($status == CONFIRM && $value == STOP_USING) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('messages.status_invalid');
    }
}
