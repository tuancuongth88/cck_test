<?php

namespace App\Jobs;

use App\Models\HR;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use Carbon\Carbon;
use Helper\Common;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class DownloadFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $timeout = 160; // Set the timeout to 1 minute
    protected $retryAfter = 60; // Retry the job after 1 minute
    public $tries = 5; // Set the maximum number of attempts to 5
    private $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rowData = [];
        $language = LanguageRequirement::query()->get()->keyBy('id')->all();
        $job_type = JobType::query()->get()->keyBy('id')->all();
        $job_info = JobInfo::query()->get()->keyBy('id')->all();
        $max = 0;

        foreach ($this->data as $key => $row) {
            $max = ($row['main_jobs_count']) > $max ? $row['main_jobs_count'] : $max;
            $rowData[$key]['corporate_name_en'] = $row['corporate_name_en'];
            $rowData[$key]['corporate_name_ja'] = $row['corporate_name_ja'];
            $rowData[$key]['country'] = null;
            $rowData[$key]['full_name'] = $row['full_name'];
            $rowData[$key]['full_name_ja'] = $row['full_name_ja'];
            $rowData[$key]['gender'] = isset($row['gender']) ? ($row['gender'] == 1 ? '男性' : '女性') : null;
            $rowData[$key]['date_of_birth'] = Carbon::parse($row['date_of_birth'])->format('Y年m月d日');
            $rowData[$key]['work_form'] = empty($row['work_form']) ? null : HRS_WORK_FORM_TEXT[$row['work_form']];
            $rowData[$key]['preferred_working_hours'] = $row['preferred_working_hours'] ?? '';
            $rowData[$key]['japanese_level'] = @HR_JAPANESE_LEVEL[$row['japanese_level']];
            $rowData[$key]['final_education_date'] = Carbon::parse($row['final_education_date'])->format('Y年m月');
            $rowData[$key]['final_education_classification'] = HR_FINAL_EDUCATION_TEXT[$row['final_education_classification']];
            $rowData[$key]['final_education_degree'] = HR_EDUCATION_DEGREES[$row['final_education_degree']];
            $rowData[$key]['major_classification'] = $row['job_type'];
            $rowData[$key]['middle_classification'] = $row['job_info'];
            for ($i = 0; $i < $row['main_jobs_count']; $i++) {
                if (isset($row['main_jobs'][$i])) {
                    $dateFrom = isset($row['main_jobs'][$i]['main_job_career_date_from']) ? Carbon::parse($row['main_jobs'][$i]['main_job_career_date_from'])->format('Y年m月') : '';
                    if (isset($row['main_jobs'][$i]['main_job_career_date_to'])) {
                        $dateTo = Carbon::parse($row['main_jobs'][$i]['main_job_career_date_to'])->format('Y年m月');
                    } elseif ($row['main_jobs'][$i]['to_now'] == 'yes') {
                        $dateTo = '現在';
                    } else {
                        $dateTo = '';
                    }
                    $job_type = @$job_type[$row['main_jobs'][$i]['department_id']];
                    $job_info = @$job_info[$row['main_jobs'][$i]['job_id']];
                    $rowData[$key]['main_job_date_' . $i] = (!$dateFrom && !$dateTo) ? '' : ($dateFrom . '〜' . $dateTo);
                    $rowData[$key]['main_job_department_' . $i] = $job_type['name_ja'] ?? '';
                    $rowData[$key]['main_job_title_' . $i] = $job_info['name_ja'] ?? '';
                    $rowData[$key]['main_job_detail_' . $i] = $row['main_jobs'][$i]['detail'] ?? '';
                } else {
                    $rowData[$key]['main_job_date_' . $i] = null;
                    $rowData[$key]['main_job_department_' . $i] = null;
                    $rowData[$key]['main_job_title_' . $i] = null;
                    $rowData[$key]['main_job_detail_' . $i] = null;
                }
            }

            $rowData[$key]['personal_pr_special_notes'] = $row['personal_pr_special_notes'] ?? '';
            $rowData[$key]['remarks'] = $row['remarks'] ?? '';
            $rowData[$key]['telephone_number'] = $row['telephone_number'] ?? '';
            $rowData[$key]['mobile_phone_number'] = $row['mobile_phone_number'] ?? '';
            $rowData[$key]['mail_address'] = $row['mail_address'];
            $rowData[$key]['address_city'] = $row['address_city'] ?? '';
            $rowData[$key]['address_district'] = $row['address_district'] ?? '';
            $rowData[$key]['address_number'] = $row['address_number'] ?? '';
            $rowData[$key]['address_other'] = $row['address_other'] ?? '';
            $rowData[$key]['hometown_city'] = $row['hometown_city'] ?? '';
            $rowData[$key]['home_town_district'] = $row['home_town_district'] ?? '';
            $rowData[$key]['home_town_number'] = $row['home_town_number'] ?? '';
            $rowData[$key]['home_town_other'] = $row['home_town_other'] ?? '';
        }

        $headers = [
            '団体名', '団体名（日本語）', '国', '氏名', '氏名（ﾌﾘｶﾞﾅ）', '性別', '生年月日', '勤務形態', '勤務形態（非常勤）', '日本語レベル',
            '最終学歴（年月）', '最終学歴（区分）', '最終学歴（学位）', '最終学歴（学科）大分類', '最終学歴（学科）中分類'
        ];

        for ($i = 1; $i <= $max; $i++) {
            $headers[] = '主な職務経歴' . mb_chr(0x245F + $i) . '（年月）';
            $headers[] = '主な職務経歴' . mb_chr(0x245F + $i) . '（所属）';
            $headers[] = '主な職務経歴' . mb_chr(0x245F + $i) . '（職名）';
            $headers[] = '主な職務経歴' . mb_chr(0x245F + $i) . '（詳細）';
        }
        $headers = array_merge($headers, [
            '自己PR・特記事項', '備考', '連絡先電話番号', '携帯電話番号', 'メールアドレス', '現住所（市）', '現住所（町）',
            '現住所（番地）', '現住所（その他）', '出身地住所（市）', '出身地住所（町）', '出身地住所（番地）', '出身地住所（その他）'
        ]);
        return $this->exportCSV($rowData, $headers, 'HR_list');
    }

    public function exportCSV($data, $columns, $fileName)
    {
        try {
            $headers = array(
                "Content-type" => "text/csv; charset=utf-8",
                "Content-Disposition" => "attachment; filename=$fileName.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0",
            );
            $callback = function () use ($data, $columns) {
                $file = fopen('php://output', 'w');
                fwrite($file, "\xEF\xBB\xBF");
                fputcsv($file, $columns);
                foreach ($data as $value) {
                    fputcsv($file, $value);
                }

                fclose($file);
            };
            $response = Response::stream($callback, 200, $headers);
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            $response->send();
            exit;
        }catch (\Exception $exception){
            Log::error($exception);
        }
    }
}
