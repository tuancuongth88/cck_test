<?php

namespace App\Rules;

use App\Models\Company;
use App\Models\HrOrganization;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Route;

class CheckEmailRule implements Rule
{
    private $id;
    private $model;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id = null, $model = null)
    {
        $this->id = $id;
        $this->model = $model;
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
        $hr = HrOrganization::where(HrOrganization::MAIL_ADDRESS, $value)->first();
        $company = Company::where(Company::MAIL_ADDRESS, $value)->first();
        if ($this->model && $this->id) {
            $object = $this->model::query()
                ->where('id', '!=', $this->id)
                ->where($this->model::MAIL_ADDRESS, $value)
                ->first();

            if ($object) {
                return false;
            } else {
                $userId = $this->model::find($this->id)->user_id;
                if ($userId){
                    $user = User::where(User::MAIL_ADDRESS, $value)->where('id', '<>', $userId)->first();
                }else{
                    $user = User::where(User::MAIL_ADDRESS, $value)->first();
                }
                if ($this->model == Company::class)
                    $object = HrOrganization::where(HrOrganization::MAIL_ADDRESS, $value)->first();
                else
                    $object = Company::where(Company::MAIL_ADDRESS, $value)->first();
                if ($user || $object)
                    return false;
            }
        } else {
            if ($hr || $company) {
                return false;
            } else {
                $user = User::where(User::MAIL_ADDRESS, $value)->first();
                if ($user)
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
        return trans('messages.email_existed');
    }
}
