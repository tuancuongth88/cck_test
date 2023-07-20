<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class ChangePasswordRequest extends FormRequest
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
            case 'confirmPassword':
                return [
                    "current_password" => "required",
                    "current_password_confirm" => "required|same:current_password",
                ];
            case 'changePassword':
                return [
                    "new_password" => ['required', 'size:12', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]+$/',
                        function ($attribute, $value, $fail) {
                            $oldPass = Auth::user()->password;
                            if(Hash::check($value, $oldPass)) {
                                $fail(trans('auth.repeat_password'));
                            }
                        }
                    ],
                    "new_password_confirm" => "required|same:new_password",
                ];
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            'new_password.*' => trans('validation.password-valid')
        ];
    }
}
