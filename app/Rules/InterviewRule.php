<?php

namespace App\Rules;

use App\Models\Entry;
use App\Models\Interview;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class InterviewRule implements Rule
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
        $arrayListIdInterview = array_unique($value);
        if ($userLogin->type == COMPANY || $userLogin->type == HR ) {
            $company = $userLogin->company;
            $hrOrg = $userLogin->hrOrganization;
            $listIdForCompany =  Interview::query()->whereIn(Interview::STATUS_SELECTION,[INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL,INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE])
                ->whereIn('id',$arrayListIdInterview);
            if ($userLogin->type == COMPANY)
                $listIdForCompany= $listIdForCompany->whereHas('work',function ($q) use ($company){
                    $q->where('company_id',$company->id);
                })->count();
            if ($userLogin->type == HR)
                $listIdForCompany= $listIdForCompany->whereHas('hr',function ($q) use ($hrOrg){
                    $q->where('hr_organization_id',$hrOrg->id);
                })->count();

            if (count($arrayListIdInterview) != $listIdForCompany){
                return false;
            }
        }
        if ($userLogin->type == COMPANY_MANAGER || $userLogin->type == SUPER_ADMIN || $userLogin->type == HR_MANAGER ) {
            $listIdForCompany =  Interview::query()->whereIn(Interview::STATUS_SELECTION,[INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL,INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE])
                ->whereIn('id',$arrayListIdInterview)
                ->count();
            if (count($arrayListIdInterview) != $listIdForCompany){
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
        return trans('api.entry.delete.request');
    }

}
