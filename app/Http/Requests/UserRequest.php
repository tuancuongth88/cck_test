<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\HrOrganization;
use App\Rules\CheckStatusAccountRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch (Route::getCurrentRoute()->getActionMethod()) {
            case 'update':
                return $this->getCustomRule();
            case 'store':
                return $this->getCustomRule();
            case 'updateStatusHR':
                return $this->getCustomRule();
            case 'updateStatusCompany':
                return $this->getCustomRule();
            default:
                return [];
        }
    }

    public function getCustomRule()
    {
        if (Route::getCurrentRoute()->getActionMethod() == 'update') {
            return [
                'name' => "required",
                'username' => "required",
//                'email'     => "required|unique:users,email,".request('id'),
            ];
        }
        if (Route::getCurrentRoute()->getActionMethod() == 'store') {
            return [
                'name' => "required",
                'email' => "email|required|unique:users,email,NULL,id,deleted_at,NULL",
                'role' => "required",
            ];
        }

        if (Route::getCurrentRoute()->getActionMethod() == 'updateStatusCompany') {
            return [
                'company_id' => 'required|exists:companies,id',
                'status' => ['required', 'in:2,3,4', new CheckStatusAccountRule($this->get('company_id'), Company::class)]
            ];
        }

        if (Route::getCurrentRoute()->getActionMethod() == 'updateStatusHR') {
            return [
                'hr_id' => 'required|exists:hr_organization,id',
                'status' => ['required','bail', 'in:2,3,4', new CheckStatusAccountRule($this->get('hr_id'), HrOrganization::class)]
            ];
        }
    }
}
