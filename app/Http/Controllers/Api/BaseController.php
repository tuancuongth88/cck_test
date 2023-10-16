<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HrOrganization;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class BaseController extends Controller
{
    public function Options(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $type = $request['type'] ?? '';
        $defaultResponse =  $this->responseJson(CODE_SUCCESS, null);
        if (!$type) {
            return $defaultResponse;
        }

        if ($type == 'hr_search') {
            return $this->responseJson(CODE_SUCCESS, $this->getOptionsHrSearch($user));
        }
        return $defaultResponse;
    }

    public function getOptionsHrSearch($user): array
    {
        $hrOrg = [];
        if (in_array($user->type, [SUPER_ADMIN, HR_MANAGER, COMPANY_MANAGER, COMPANY])) {
            $hrOrg = HrOrganization::select('id', 'corporate_name_en')
                ->get()
            ;
        }
        $startAges = 20;
        $endAges = 65;
        $years = 1990;
        $majorList = JobType::select('id', 'name_ja')
            ->where('type', 2)
            ->with([
                'jobInfo' => function ($q) {
                    $q->select('id', 'job_type_id', 'name_ja');
                }
            ])
            ->get();

        $majorList2 = JobType::select('id', 'name_ja')
            ->where('type', 1)
            ->with([
                'jobInfo' => function ($q) {
                    $q->select('id', 'job_type_id', 'name_ja');
                }
            ])
            ->get();
        $japanLevels = LanguageRequirement::select('id', 'name')->get();
        return [
            'hr_orgs' => $hrOrg ?? null,
            'genders' => [
                HRS_GENDER_MALE => trans('male'),
                HRS_GENDER_FEMALE => trans('female'),
            ],
            'final_educations' => [
                HRS_CLASSIFICATION_GRADUATION => '卒業',
                HRS_CLASSIFICATION_FINISH => '卒業見込み',
                HRS_CLASSIFICATION_DROPOUT => '中退',
            ],
            'education_degrees' => [
                1 => '大学卒業以上',
                2 => '大学卒業以外'
            ],
            'major_class' => $majorList,
            'years' => range(date('Y'), $years, -1),
            'months' => range(1, 12),
            'ages' => range($startAges, $endAges),
            'work_forms' => [
                HRS_FULL_TIME_EMPLOYEE => '正社員',
                HRS_CONTRACT_EMPLOYEE => '正社員',
                HRS_TEMPORARY_EMPLOYEE => '正社員',
                HRS_OTHER_EMPLOYEE => 'その他'

            ],
            'japan_levels' => $japanLevels,
            'main_jobs' => $majorList2
        ];
    }

}
