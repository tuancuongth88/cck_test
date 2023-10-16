<?php

namespace App\Rules;

use App\Models\Entry;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class EntryRule implements Rule
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
        $arrayListIdEntry = array_unique($value);
        if ($userLogin->type == COMPANY || $userLogin->type == HR ) {
            $company = $userLogin->company;
            $hrOrg = $userLogin->hrOrganization;
            $listIdForCompany =  Entry::query()->where(Entry::STATUS,'!=',ENTRY_STATUS_REQUESTING)
                ->whereIn(Entry::DISPLAY,['on', 'stop'])
                ->whereIn('id',$arrayListIdEntry);
            if ($userLogin->type == COMPANY)
                $listIdForCompany= $listIdForCompany->whereHas('work',function ($q) use ($company){
                    $q->where('company_id',$company->id);
                })->count();
            if ($userLogin->type == HR)
                $listIdForCompany= $listIdForCompany->whereHas('hr',function ($q) use ($hrOrg){
                    $q->where('hr_organization_id',$hrOrg->id);
                })->count();
            if (count($arrayListIdEntry) != $listIdForCompany){
                return false;
            }
        }
        if ($userLogin->type == COMPANY_MANAGER || $userLogin->type == SUPER_ADMIN || $userLogin->type == HR_MANAGER ) {
            $listIdForCompany =  Entry::query()->where(Entry::STATUS,'!=',ENTRY_STATUS_REQUESTING)
                ->whereIn('id',$arrayListIdEntry)
                ->count();
            if (count($arrayListIdEntry) != $listIdForCompany){
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
