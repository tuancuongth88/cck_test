<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace Repository;

use App\Models\Result;
use App\Models\UserFavorite;
use App\Repositories\Contracts\ResultRepositoryInterface;
use Carbon\Carbon;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Database\Eloquent\Model;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class ResultRepository extends BaseRepository implements ResultRepositoryInterface
{

     public function __construct(Application $app)
     {
         parent::__construct($app);
         $this->scopeQuery(function ($query) {
             if (Auth::user()->type == HR) {
                 $query = $query->where('hrs.user_id', Auth::user()->id);
             }
             if (Auth::user()->type == COMPANY) {
                 $query = $query->where('works.user_id', Auth::user()->id);
             }
             return $query->where('display', 'on');
         });
     }

    /**
       * Instantiate model
       *
       * @param Result $model
       */

    public function model()
    {
        return Result::class;
    }

    public function list($attributes, $paginate = true)
    {
        $mainTbl = $this->getTableName();
        $items = $this->select('*', "$mainTbl.id", "$mainTbl.updated_at", "$mainTbl.status_selection")->with([
            'hr' => function($q) {
                $q->select('id', 'hrs.user_id');
            },
            'work' => function($q) {
                $q->select('id', 'works.user_id', 'title');
            },
        ]);
        $items->join('hrs as h1', 'h1.id', '=', "$mainTbl.hr_id");
        $items->join('works as w1', 'w1.id', '=', "$mainTbl.work_id");
        $ids = $attributes['ids'] ?? '';
        if ($ids) {
            $items->whereIn("$mainTbl.id", $ids);
        }

        $items = HRRepository::searchFilter($items, $attributes);

        if (isset($attributes['field']) && $attributes->field) {
            $field = $attributes->field;
            $sortBy = $attributes->sort_by;
            if ($field == 'job_title') {
                $attributes->field = 'title';
            }
            if ($field == 'updating_date') {
                $attributes->field = 'updated_at';
            }
            if ($field == 'status_selection') {
                $items = Common::sortArrayText($items, "$mainTbl.status_selection", ENTRY_STATUS_TEXTS, $sortBy == 'asc');
            } else {
                $items = Common::sortBy($attributes, $items);
            }
        }else{
            $items = $items->orderBy("$mainTbl.id", 'desc');
        }
        if ($paginate) {
            $items = Common::pagination($attributes, $items);
        }
        $updateItems = [];
        $items->each(function ($item) use (&$updateItems) {
            $updateItems[] = [
                'id' => $item->id,
                'code' => $item->code,
                'updating_date' => $item->updated_at->format('Ymd'),
                'hr_id' => $item->hr_id,
                'full_name' => $item->full_name,
                'full_name_ja' => $item->full_name_ja,
                'job_id' => $item->work_id,
                'job_title' => $item->title,
                'status_selection' => $item->status_selection,
                'status_selection_name' => RESULT_STATUS_LIST[$item->status_selection],
                'time' => $item->time,
                'is_favorite_hrs' => UserFavorite::isFavorite($item->hr_id, FAVORITE_TYPE_HRS),
                'is_favorite_job' => UserFavorite::isFavorite($item->work_id, FAVORITE_TYPE_WORK),
            ];
        });
        if ($paginate) {
            $items->setCollection(collect($updateItems));
        }

        return $items;
    }

    public function isOwner($ids = [])
    {
        $items = $this->findWhereIn('id', $ids)
            ->whereIn('status_selection', [RESULT_STATUS_SELECTION_NOT_PASS, RESULT_STATUS_SELECTION_OFFER_DECLINE]);
        return $items->count() == count($ids);
    }

    public function hide($ids){
        try {
            $listData = $this->model->whereIn('id', $ids)->get();
            foreach ($listData as $item) {
                if (in_array($item->status_selection, [RESULT_STATUS_SELECTION_NOT_PASS, RESULT_STATUS_SELECTION_OFFER_DECLINE])){
                    $item->display = 'off';
                    $item->save();
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(array $attributes, $id)
    {
        $user = Auth::user();
        $ids['ids'] = [$id];
        $result = $this->list($ids, false)->where('time', '>', Carbon::now()->format('Ymd'))->first();
        if (!$result){
            return ResponseService::responseJsonError(CODE_NOT_FOUND, trans('messages.data_does_not_exist'));
        }
        switch ($result->status_selection){
            case RESULT_STATUS_SELECTION_OFFER:
                if (in_array($user->type, [HR, HR_MANAGER, SUPER_ADMIN])){
                    $attributes['status_selection'] = $attributes['status'];
                    if ($attributes['status'] == RESULT_STATUS_SELECTION_OFFER_DECLINE && !isset($attributes['note'])){
                        return ResponseService::responseJsonError(HTTP_UNPROCESSABLE_ENTITY, __('api.results.node.required'));
                    }
                    return $this->updateAndSendNotify($id, $attributes, $attributes['status']);
                }else{
                    return ResponseService::responseJsonError(CODE_UNAUTHORIZED, trans('messages.mes.permission'));
                }
            case RESULT_STATUS_SELECTION_OFFERl_CONFIRM:
                if (in_array($user->type, [COMPANY_MANAGER, SUPER_ADMIN])){
                    if (!isset($attributes['hire_date'])){
                        return ResponseService::responseJsonError(HTTP_UNPROCESSABLE_ENTITY, __('api.results.hire_date.required'));
                    }
                    return $this->updateAndSendNotify($id, $attributes, RESULT_STATUS_SELECTION_OFFER_CONFIRM);
                }else{
                    return ResponseService::responseJsonError(CODE_UNAUTHORIZED, trans('messages.mes.permission'));
                }
        }
        return  ResponseService::responseJsonError(CODE_UNAUTHORIZED, trans('messages.mes.permission'));
    }

    public function updateAndSendNotify($id, $attributes, $status){
        parent::update($attributes, $id);
        //send notification
        return $this->detail($id);
    }

    /**
     * @param $id
     * @return array|false
     */
    public function detail($id)
    {
        $mainTbl = (new Result())->getTable();
        $item = $this->select("*", "$mainTbl.id", 'hrs.full_name', "$mainTbl.status_selection")
            ->join('hrs', 'hrs.id', '=', "$mainTbl.hr_id")
            ->join('works', 'works.id', '=', "$mainTbl.work_id")
            ->where($mainTbl.'.display', 'on')
            ->where($mainTbl.".id", $id)
            ->first();
        if (!$item) return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.data_does_not_exist'));
        $updateItems = [
            'id' => $item->id,
            'code' => $item->code,
            'hr_id' => $item->hr_id,
            'full_name' => $item->full_name,
            'full_name_ja' => $item->full_name_ja,
            'job_id' => $item->work_id,
            'job_title ' => $item->title,
            'status_selection' => $item->status_selection,
            'time' => $item->time,
            'hire_date' => $item->hire_date,
            'display' => $item->display,
            'is_favorite_hrs' => UserFavorite::isFavorite($item->hr_id, FAVORITE_TYPE_HRS),
            'is_favorite_job' => UserFavorite::isFavorite($item->work_id, FAVORITE_TYPE_WORK),
            'interview_info' => $item->interview->infors
        ];
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success',$updateItems);
    }

}
