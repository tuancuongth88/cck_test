<?php


namespace Helper;

use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Common
{

    public static function uploadFile(UploadedFile $file, $path = '', $userId = null)
    {
        $userId = $userId ?? Auth::id();
        $fileName = time().'.'.$file->getClientOriginalExtension();
        return $file->storeAs($path, $fileName);
    }

    public static function uploadFileWithCustomName(UploadedFile $file, $path = '', $originalName = '', $format_time = 'Ymd')
    {
        $time = $format_time ? Carbon::now()->format($format_time) : '';
        $originalName = $originalName ? $originalName : '';
        $fileName = $originalName . $time . '.' . $file->getClientOriginalExtension();
        return (object) [
            'path_file' => $file->storeAs($path, $fileName),
            'name_file' => $fileName,
        ];
    }


    public static function getOptionOnTable($model, $key, $value){
        return $model::get()->pluck($value, $key)->toArray();
    }

    public static function sortBy($request, $model)
    {
        if ($request->has('sort_by') && $request->sort_by) {
            $model = $model->orderByRaw("$request->field  $request->sort_by");
        } else {
            $model = $model->orderByRaw("$request->field IS NULL, $request->field");
        }
        return $model;
    }
    public static function convertCsvToArray($path, $limit = 500)
    {
        $getEncoding = mb_detect_encoding(file_get_contents($path), mb_list_encodings(), true);
        if($getEncoding == 'SJIS'){
            $getEncoding = "SJIS-win";
        }
        $file = fopen($path, 'r');
        $delimiter = "\n";
        $header = null;
        $iterator = 0;
        $data = array();
        $array_header = [
            '団体名', '団体名（日本語）', '国', '氏名', '氏名（ﾌﾘｶﾞﾅ）', '性別', '生年月日', '勤務形態', '勤務形態（非常勤）', '日本語レベル',
            '最終学歴（年月）', '最終学歴（区分）', '最終学歴（学位）', '最終学歴（学科）大分類', '最終学歴（学科）中分類',
            '主な職務経歴' . mb_chr(0x245F + 1) . '（年月）',
            '主な職務経歴' . mb_chr(0x245F + 1) . '（所属）',
            '主な職務経歴' . mb_chr(0x245F + 1) . '（職名）',
            '主な職務経歴' . mb_chr(0x245F + 1) . '（詳細）',
            '自己PR・特記事項', '備考', '連絡先電話番号', '携帯電話番号', 'メールアドレス', '現住所（市）', '現住所（町）',
            '現住所（番地）', '現住所（その他）', '出身地住所（市）', '出身地住所（町）', '出身地住所（番地）', '出身地住所（その他）'
        ];

        while (($row = fgetcsv($file, 100000, $delimiter)) !== false) {
            $is_mul_1000 = false;
            if (!mb_check_encoding($row, 'UTF-8')) {
                $row = mb_convert_encoding($row, 'UTF-8', $getEncoding);
            }
            if (!$header) {
                $array = explode(',', $row[0]);
                $array[0] = trim($array[0], "\xEF\xBB\xBF");
                $intersect = array_intersect($array_header, $array);

                if(array_search('', $array) || count($intersect) != count($array_header)) {
                    $data = ['error' => 'インポートファイルのフォーマットが正しくありません。'];
                }
                $header = $row;
            } else {
                $iterator++;
                $data[] = array_combine($header, $row);
                if ($iterator != 0 && $iterator % $limit == 0) {
                    $is_mul_1000 = true;
                    $chunk = $data;
                    $data = array();
                    yield $chunk;
                }
            }
        }
        fclose($file);
        if (!$is_mul_1000) {
            yield $data;
        }
        return;
    }

    public static function sortNumber($request, $model)
    {
        if ($request->has('sort_by') && $request->sort_by) {
            $model = $model->orderByRaw($request->field ." IS NULL, CAST(" . $request->field . " as FLOAT)" . $request->sort_by . " ");
        } else {
            $model = $model->orderByRaw($request->field . " IS NULL ,CAST(" . $request->field . " as FLOAT)");
        }
        return $model;
    }

    public static function sortArrayText($items, $field, $arr, $sortAsc = true)
    {
        if ($sortAsc) {
            asort($arr);
        } else {
            arsort($arr);
        }
        $joinedKeys = implode(',', array_keys($arr));
        if (!$arr || !$joinedKeys) {
            return $items;
        }
        return $items->orderByRaw("FIELD($field, $joinedKeys)");
    }

    public static function pagination($request, $data)
    {
        if ($request->per_page < 0) {
            $object = $data->get()->count();
            return $data->paginate($object);
        } else {
            $limit = is_null(request('per_page')) ? config('repository.pagination.limit', 15) : request('per_page');
            return $data->paginate($limit);
        }
    }
    public static function updateStatus($attributes, $model, $id)
    {
        $user = auth()->user();
        $userType = $user->type;
        $userId = $user->id;
        $status = $attributes['status'];
        $model = $model::find($id);
        if (!$model){
            return self::resp(CODE_NO_ACCESS, trans('messages.mes.permission'));
        }
        if ((in_array($userType, [HR, HR_MANAGER]) && $status != ENTRY_STATUS_DECLINE) || (in_array($userType, [COMPANY, COMPANY_MANAGER]) && !in_array($status, [ENTRY_STATUS_REJECT])))  {
            return self::resp(CODE_SUCCESS, trans('messages.status_invalid'));
        }
        if ($userType == HR && $model->hr->user_id != $userId) {
            return self::resp(CODE_NO_ACCESS, trans('messages.mes.permission'));
        }
        if ($userType == COMPANY && $model->work->user_id != $userId) {
            return self::resp(CODE_NO_ACCESS, trans('messages.mes.permission'));
        }
        $whyReject = '';
        $only = ['status'];
        if ($status == ENTRY_STATUS_DECLINE) {
            $only[] = 'why_reject';
            $whyReject = $attributes['why_reject'] ?? '';
        }
        if ($whyReject == 10) {
            $only[] = 'other_note';
        }
        $attributes = $attributes->only($only);
        $model->update($attributes);
        return self::resp(CODE_SUCCESS, '', $model);
    }
    public static function resp($code = CODE_SUCCESS, $message = null, $data = [], $messageContent = '', $internalMessage = '')
    {
        if ($code !== CODE_SUCCESS) {
            return ResponseService::responseJsonError($code, $message, $messageContent, $internalMessage);
        }
        return ResponseService::responseJson($code, $data, $message);
    }

    public static function exportCSV($data, $columns, $fileName)
    {
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

        return response()->stream($callback, CODE_SUCCESS, $headers);
    }

    public static function searchWork($request, $repository){
        $userId = auth()->id();
        $data = $repository->with(['passion', 'languageRequirements', 'company', 'city', 'majorClassification', 'middleClassification'])
            ->select('works.*',
                DB::raw('(CASE WHEN user_favorites.relation_id IS NOT NULL THEN true ELSE false END) AS is_favorite'))

            ->leftJoin('user_favorites', function($join) use ($userId) {
                $join->on('works.id', '=', 'user_favorites.relation_id')
                    ->where('user_favorites.user_id', '=', $userId)
                    ->where('user_favorites.type', FAVORITE_TYPE_WORK);
            });
        if ($request->has('company_id') && $request->company_id){
            $data = $data->where('company_id', $request->input('company_id'));
        }
        if ($request->has('middle_classification_id') && $request->middle_classification_id){
            $listIds = $request->input('middle_classification_id');
            $data = $data->whereIn('middle_classification_id', $listIds);
        }
        if ($request->has('income_from') && $request->income_from) {
            $data = $data->where('expected_income', '>=', $request->input('income_from'));
        }
        if ($request->has('income_to') && $request->income_to) {
            $data = $data->where('expected_income', '<=', $request->input('income_to'));
        }
        if ($request->has('city_id') && $request->city_id) {
            $data = $data->where('city_id', $request->input('city_id'));
        }
        if ($request->has('language_requirements') && $request->language_requirements) {
            $listIds = $request->input('language_requirements');
            $data = $data->whereIn('works.id', function ($query) use ($listIds)
            {
                $query->select('work_id')
                    ->from('language_requirement_works')
                    ->whereIn('language_requirement_id', $listIds);
            });
        }
        if ($request->has('form_of_employment') && $request->form_of_employment) {
            $data = $data->whereIn('form_of_employment', $request->input('form_of_employment'));
        }
        if ($request->has('passion_works') && $request->passion_works) {
            $listIds = $request->input('passion_works');
            $data = $data->whereIn('works.id', function ($query) use ($listIds)
            {
                $query->select('work_id')
                    ->from('passion_works')
                    ->whereIn('passion_id', $listIds);
            });
        }
        if ($request->has('key_search') && $request->key_search) {
            $keySearch = $request->input('key_search');
            $data = $data->whereIn('company_id', function ($query) use ($keySearch)
            {
                $query->select('id')
                    ->from('companies')
                    ->where('company_name', 'like', '%'.$keySearch.'%');
            });
        }

        return $data;
    }

//    public static function searchW

    /**
     * @param $workId
     * @return bool
     */
    public static function checkRecruitmentDeadline($workId){
        $work = Work::find($workId);
        if (!$work) return false;
        $now = Carbon::now()->toDateString();
        $startWork = $work->application_period_from;
        $endWork = $work->application_period_to;
        if (strtotime($startWork) > strtotime($now) || strtotime($now) > strtotime($endWork)){
            return false;
        }
        return true;
    }
}
