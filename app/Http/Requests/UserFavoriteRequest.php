<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Http\Requests;

use App\Rules\CheckFavorite;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class UserFavoriteRequest extends FormRequest
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
          switch (Route::getCurrentRoute()->getActionMethod()){
                case 'index':
                    return $this->getCustomRule();
                case 'store':
                case 'destroy':
                    return $this->getCustomRule();
                default:
                    return [];
          }
    }

     public function getCustomRule(){
        if(Route::getCurrentRoute()->getActionMethod() == 'index'){
            if(Auth::user()->type == SUPER_ADMIN)
                return [
                    'type' => ['bail', 'required', Rule::in([FAVORITE_TYPE_HRS, FAVORITE_TYPE_WORK])]
                ];
            return [];
        }
        if(Route::getCurrentRoute()->getActionMethod() == 'store' || Route::getCurrentRoute()->getActionMethod() == 'destroy'){
            return  [
                'relation_id' => ['bail', 'required', new CheckFavorite($this->get('type'))],
                'type' => ['bail', 'required', Rule::in([FAVORITE_TYPE_HRS, FAVORITE_TYPE_WORK]), new CheckFavorite($this->get('type'), true)],
            ];
        }
     }

    public function messages()
    {
        return [
            'required' => ':attribute not null'
        ];
    }
}
