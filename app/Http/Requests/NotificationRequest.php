<?php
/**
 * Created by VeHo.
 * Year: 2023-07-04
 */

namespace App\Http\Requests;

use App\Rules\CheckStatusAccountRule;
use App\Rules\NotificationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class NotificationRequest extends FormRequest
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
            case 'update':
                return $this->getCustomRule();
            case 'store':
                return $this->getCustomRule();
            default:
                return [];
        }
    }

     public function getCustomRule(){
        if(Route::getCurrentRoute()->getActionMethod() == 'update'){
            return [

            ];
        }
        if(Route::getCurrentRoute()->getActionMethod() == 'store'){
            return  [
                'title' => 'required|max:50',
                'text' => 'required|max:1000',
                'image_id' =>['nullable', new NotificationRule()]
            ];
        }
     }

    public function messages()
    {
        return [
            'title.required' => trans('api.noti.title.required'),
            'title.max' => trans('api.noti.title.max'),
            'text.required' => trans('api.noti.text.required'),
            'text.max' => trans('api.noti.text.max'),
        ];
    }
}
