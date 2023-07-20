<?php

namespace App\Rules;

use App\Models\HR;
use App\Models\Work;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CheckFavorite implements Rule
{
    protected $type;
    protected $msg;
    protected $is_check_type;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type, $is_check_type = false)
    {
        $this->type = $type;
        $this->is_check_type = $is_check_type;
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
        if($this->is_check_type) {
            $type_user = Auth::user()->type;
            if ($type_user == HR_MANAGER || $type_user == HR) {
                if($this->type != FAVORITE_TYPE_WORK) {
                    $this->msg = trans('api.favorite.checkFavoriteType', ['object' => 'Job']);
                    return false;
                }
            }

            if ($type_user == COMPANY_MANAGER || $type_user == COMPANY) {
                if($this->type != FAVORITE_TYPE_HRS) {
                    $this->msg = trans('api.favorite.checkFavoriteType', ['object' => 'HRs']);
                    return false;
                }
            }
        } else {
            if($this->type == FAVORITE_TYPE_HRS) {
                $hr = HR::query()->where('id', $value)->where('status', HRS_STATUS_PUBLIC)->first();
                if(!$hr) {
                    $this->msg = trans('api.favorite.checkFavoriteHr');
                    return false;
                }
            }

            if($this->type == FAVORITE_TYPE_WORK) {
                $work = Work::query()->where('id', $value)->where('status', WORK_STATUS_RECRUITING)->first();
                if(!$work) {
                    $this->msg = trans('api.favorite.checkFavoriteJob');
                    return false;
                }
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
        return $this->msg;
    }
}
