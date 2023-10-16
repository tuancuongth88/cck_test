<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class ResultRequest extends FormRequest
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
          return $this->getCustomRule();
    }

     public function getCustomRule(){
        if(Route::getCurrentRoute()->getActionMethod() == 'update'){
            return [
                'status' => 'nullable|numeric|in:1,3,4',
                'note' => 'nullable|string|max:1000',
                'hire_date' => 'nullable|date|date_format:Y-m-d',
                'time' => 'nullable|date|date_format:Ymd',
            ];
        }
        if(Route::getCurrentRoute()->getActionMethod() == 'store'){
            return  [

            ];
        }
        if (Route::getCurrentRoute()->getActionMethod() == 'hide'){
            return [
                'ids' => ['required','array'],
                'ids.*' => 'required|exists:results,id',
            ];
        }
        return [];
     }

    public function messages()
    {
        return [
            'required' => ':attribute not null',
            'status.in' => __('api.result.status'),
            'hire_date.date_format' => __('api.result.hire_date.date_format')
        ];
    }
}
