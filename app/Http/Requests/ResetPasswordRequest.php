<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\CheckTokenRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class ResetPasswordRequest extends FormRequest
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
            case 'forgetPassword':
                return [
                    "mail_address" => "email|required|exists:users,mail_address"
                ];
            case 'resetPassword':
                return [
                    "token" => ['required', new CheckTokenRule()],
                    "mail_address" => 'required|email|exists:users,mail_address',
                    "new_password" => ['required', 'size:12', 'regex:/^(?=.*[A-Z])(?=.*\d).{12,}$/',
                        function ($attribute, $value, $fail) {
                            $user = User::query()->where(User::MAIL_ADDRESS, $this->input('mail_address'))->first();
                            if($user) {
                                $oldPass = $user->password;
                                if(Hash::check($value, $oldPass)) {
                                    $fail(trans('auth.repeat_password'));
                                }
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
            'new_password.*' => trans('validation.password-valid'),
            'mail_address.exists' => trans('errors.email_not_exist')
        ];
    }
}
