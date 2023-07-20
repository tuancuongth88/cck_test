<?php

namespace App\Rules;

use App\Models\HR;
use App\Models\Offer;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class HRRule implements Rule
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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $userLogin = Auth::user();
        $arrayListIdHR = array_unique($value);
        if ($userLogin->type == HR) {
            $listIdForHR =  HR::query()->whereIn('id',$arrayListIdHR)->where('user_id',$userLogin->id)
                ->count();
            if (count($arrayListIdHR) != $listIdForHR){
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
        return trans('api.offer.delete.request');
    }
}
