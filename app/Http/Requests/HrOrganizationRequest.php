<?php

namespace App\Http\Requests;

use App\Models\HrOrganization;
use App\Rules\CheckEmailRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class HrOrganizationRequest extends FormRequest
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
            case 'store':
                return $this->getCustomRule();
            case 'update':
                return $this->getCustomRule();
            default:
                return [];
        }
    }

    public function getCustomRule()
    {
        if (Route::getCurrentRoute()->getActionMethod() == 'store') {
            return [
                HrOrganization::CORPORATE_NAME_EN => 'required|max:50',
                HrOrganization::CORPORATE_NAME_JA => 'required|max:50',
                HrOrganization::LICENSE_NO => 'required|max:50',
                HrOrganization::ACCOUNT_CLASSIFICATION => ['required', Rule::in([ACC_CLASS_OWN_PLATFORM, ACC_CLASS_SEND_AGENCY, ACC_CLASS_DISPATCH_AGENCY, ACC_CLASS_SCHOOL, ACC_CLASS_COMPANY])],
//                HrOrganization::COUNTRY => 'required|numeric|min:1|max:7',
                HrOrganization::REPRESENTATIVE_FULL_NAME => 'required|max:50',
                HrOrganization::REPRESENTATIVE_FULL_NAME_FURIGANA => 'required|max:50',
                HrOrganization::REPRESENTATIVE_CONTACT => 'nullable',
                HrOrganization::ASSIGNEE_CONTACT => 'required',
                HrOrganization::POST_CODE => 'required|regex:/^[\d\-]{1,10}$/',
                HrOrganization::PREFECTURES => 'required|max:50',
                HrOrganization::MUNICIPALITY => 'required|max:50',
                HrOrganization::NUMBER => 'required|max:50',
                HrOrganization::OTHER => 'nullable|max:50',
                HrOrganization::MAIL_ADDRESS => ['required','max:50','email', new CheckEmailRule()],
                HrOrganization::URL => 'required|url',
                HrOrganization::CERTIFICATE_FILE => 'required|exists:files,id',
                'is_create' => 'required|in:0,1'
            ];
        }
        if (Route::getCurrentRoute()->getActionMethod() == 'update') {
            return [
                HrOrganization::CORPORATE_NAME_EN => 'required|max:50',
                HrOrganization::CORPORATE_NAME_JA => 'required|max:50',
                HrOrganization::LICENSE_NO => 'required|max:50',
                HrOrganization::ACCOUNT_CLASSIFICATION => ['required', Rule::in([ACC_CLASS_OWN_PLATFORM, ACC_CLASS_SEND_AGENCY, ACC_CLASS_DISPATCH_AGENCY, ACC_CLASS_SCHOOL, ACC_CLASS_COMPANY])],
//                HrOrganization::COUNTRY => 'required|numeric|min:1|max:7',
                HrOrganization::REPRESENTATIVE_FULL_NAME => 'required|max:50',
                HrOrganization::REPRESENTATIVE_FULL_NAME_FURIGANA => 'required|max:50',
                HrOrganization::REPRESENTATIVE_CONTACT => 'nullable',
                HrOrganization::ASSIGNEE_CONTACT => 'required',
                HrOrganization::POST_CODE => 'required|regex:/^[\d\-]{1,10}$/',
                HrOrganization::PREFECTURES => 'required|max:50',
                HrOrganization::MUNICIPALITY => 'required|max:50',
                HrOrganization::NUMBER => 'required|max:50',
                HrOrganization::OTHER => 'nullable|max:50',
                HrOrganization::MAIL_ADDRESS => ['required','max:50','email', new CheckEmailRule($this->route('id'), HrOrganization::class)],
                HrOrganization::URL => 'required|url',
                HrOrganization::CERTIFICATE_FILE => 'required|exists:files,id',
            ];
        }
    }

    public function attributes()
    {
        return [
            HrOrganization::CORPORATE_NAME_EN => '法人名',
            HrOrganization::CORPORATE_NAME_JA => '法人名（日本語）',
            HrOrganization::LICENSE_NO => 'ライセンス番号',
            HrOrganization::ACCOUNT_CLASSIFICATION => 'アカウント区分',
            HrOrganization::COUNTRY => '国',
            HrOrganization::REPRESENTATIVE_FULL_NAME => '代表者氏名',
            HrOrganization::REPRESENTATIVE_FULL_NAME_FURIGANA => '代表者氏名（フリガナ）',
            HrOrganization::REPRESENTATIVE_CONTACT => '代表者連絡先',
            HrOrganization::ASSIGNEE_CONTACT => '担当者連絡先',
            HrOrganization::POST_CODE => '郵便番号',
            HrOrganization::PREFECTURES => '都道府県',
            HrOrganization::MUNICIPALITY => '市町村区',
            HrOrganization::NUMBER => '番地',
            HrOrganization::OTHER => 'その他',
            HrOrganization::MAIL_ADDRESS => 'メールアドレス',
            HrOrganization::CERTIFICATE_FILE => '許可証',
        ];
    }
}
