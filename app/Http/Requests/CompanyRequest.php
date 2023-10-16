<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Rules\CheckEmailRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class CompanyRequest extends FormRequest
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

    public function getCustomRule()
    {
        if (Route::getCurrentRoute()->getActionMethod() == 'store') {
            return [
                Company::COMPANY_NAME => 'required|max:50',
                Company::COMPANY_NAME_JP => 'required|max:50',
                Company::MAJOR_CLASSIFICATION => 'required|exists:job_type,id,type,1',
                Company::MIDDLE_CLASSIFICATION => 'required|exists:job_info,id,job_type_id,'.$this->input('major_classification'),
                Company::POST_CODE => 'required|max:7',
                Company::PREFECTURES => 'required|max:50',
                Company::MUNICIPALITY => 'required|max:50',
                Company::NUMBER => 'required|max:50',
                Company::OTHER => 'nullable|max:50',
                Company::TELEPHONE_NUMBER => 'required',
                Company::MAIL_ADDRESS => ['required','max:50','email', new CheckEmailRule()],
                Company::URL => 'required|url',
                Company::JOB_TITLE => 'required|max:50',
                Company::FULL_NAME => 'required|max:50',
                Company::FULL_NAME_FURIGANA => 'required|max:50',
                Company::REPRESENTATIVE_CONTACT => 'nullable',
                Company::ASSIGNEE_CONTACT => 'required',
                'is_create' => 'required|in:0,1'
            ];
        }
        if (Route::getCurrentRoute()->getActionMethod() == 'update'){
            return [
                Company::COMPANY_NAME => 'required|max:50',
                Company::COMPANY_NAME_JP => 'required|max:50',
                Company::MAJOR_CLASSIFICATION => 'required|exists:job_type,id,type,1',
                Company::MIDDLE_CLASSIFICATION => 'required|exists:job_info,id,job_type_id,'.$this->input('major_classification'),
                Company::POST_CODE => 'required|max:7',
                Company::PREFECTURES => 'required|max:50',
                Company::MUNICIPALITY => 'required|max:50',
                Company::NUMBER => 'required|max:50',
                Company::OTHER => 'nullable|max:50',
                Company::TELEPHONE_NUMBER => 'required',
                Company::MAIL_ADDRESS => ['required','max:50','email', new CheckEmailRule($this->route('id'), Company::class)],
                Company::URL => 'required|url',
                Company::JOB_TITLE => 'required|max:50',
                Company::FULL_NAME => 'required|max:50',
                Company::FULL_NAME_FURIGANA => 'required|max:50',
                Company::REPRESENTATIVE_CONTACT => 'nullable',
                Company::ASSIGNEE_CONTACT => 'required',
            ];
        }
    }

    public function attributes(): array
    {
        return [
            Company::COMPANY_NAME => '企業名',
            Company::COMPANY_NAME_JP => '企業名（日本語）',
            Company::MAJOR_CLASSIFICATION => '大分類',
            Company::MIDDLE_CLASSIFICATION => '中分類',
            Company::POST_CODE => '郵便番号',
            Company::PREFECTURES => '都道府県',
            Company::MUNICIPALITY => '都道府県',
            Company::NUMBER => '都道府県',
            Company::OTHER => '都道府県',
            Company::TELEPHONE_NUMBER => '電話番号',
            Company::MAIL_ADDRESS => 'メールアドレス',
            Company::JOB_TITLE => '肩書き',
            Company::FULL_NAME => '氏名',
            Company::FULL_NAME_FURIGANA => '氏名（フリガナ）',
            Company::REPRESENTATIVE_CONTACT => '代表者連絡先',
            Company::ASSIGNEE_CONTACT => '担当者連絡先',
        ];
    }
}
