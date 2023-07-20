<?php

namespace App\Rules;

use App\Models\Offer;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class OfferRule implements Rule
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
        $arrayListIdOffer = array_unique($value);
        if ($userLogin->type == COMPANY) {
            $company = $userLogin->company;
            $listIdForCompany =  Offer::query()->where(Offer::STATUS,'!=',OFFER_STATUS_REQUESTING)
                ->whereIn('id',$arrayListIdOffer)
                ->whereHas('work',function ($q) use ($company){
                    $q->where('company_id',$company->id);
                })->count();
            if (count($arrayListIdOffer) != $listIdForCompany){
                return false;
            }
        }
        if ($userLogin->type == COMPANY_MANAGER || $userLogin->type == SUPER_ADMIN ) {
            $listIdForCompany =  Offer::query()->where(Offer::STATUS,'!=',OFFER_STATUS_REQUESTING)
                ->whereIn('id',$arrayListIdOffer)
                ->count();
            if (count($arrayListIdOffer) != $listIdForCompany){
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
