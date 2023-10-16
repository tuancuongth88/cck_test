<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ValidateMainJobs implements Rule
{
    private $message;
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
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Kiểm tra xem mảng ở vị trí 0 có tồn tại và có giá trị lớn hơn 0 hay không
        if (isset($value[0])) {
            // Nếu có thì validate theo các rule đã có
            $validator = Validator::make($value, [
                '*.main_job_career_date_from' => 'nullable|date_format:Y-m',
                '*.to_now' => 'sometimes|in:yes,no',
                '*.main_job_career_date_to' => 'nullable|date_format:Y-m',
                '*.department_id' => 'nullable|exists:job_type,id,type,'.JOB_TYPE_COL_1,
                '*.job_id' => 'nullable|exists:job_info,id',
                '*.detail' => 'nullable|string|max:2000',
            ]);
            if ($validator->fails()){
                $this->message = $validator->errors()->first();
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
        return $this->message;
    }
}
