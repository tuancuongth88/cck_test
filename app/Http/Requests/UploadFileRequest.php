<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class UploadFileRequest extends FormRequest
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
            default:
                return [];
        }
    }

    public function getCustomRule()
    {
        if (Route::getCurrentRoute()->getActionMethod() == 'store') {
            $allowedMimes = [
                'image/png',
                'image/jpeg',
                'audio/mpeg',
                'video/mpeg',
                'video/mp4',
                'video/3gpp',
                'image/gif',
                'image/svg+xml',
                'text/csv',
                'text/plain',
                'application/pdf',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ];
            $mimes = [
                'png','jpeg','mp3','mpeg','mp4','3gp','gif','svg','jpg','csv','txt','pdf','xlsx'
            ];
           return [
               'file' => [
                'required',
                'max:3072',
                function ($attribute, $value, $fail) use ($allowedMimes, $mimes) {
                    if ($value instanceof \Illuminate\Http\UploadedFile) {
                        $mime = $value->getMimeType();
                        $extension = $value->getClientOriginalExtension();
                        if (!in_array($mime, $allowedMimes) || !in_array($extension, $mimes)) {
                            $fail(trans('api.uploadFile.size'));
                        }
                    }
                },
            ],
           ];
//            return [
//                "file" => [
//                    "required|file|max:3072",
//                    "mimes:png,jpeg,mp3,mpeg,mp4,3gp,gif,svg,jpg,csv,txt,pdf,xlsx"
////                    'mimetypes:image/jpeg,image/png',
//                ],
//                'model_file' => 'nullable',
//                'type' => 'nullable|required_if:model_file,entry|in:' . implode(',', ENTRIES_FILE_TYPES)
//            ];
        }
    }

    public function messages()
    {
        return [
            'file.max' => trans('api.uploadFile.size'),
        ];
    }
}
