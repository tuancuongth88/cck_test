<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace Repository;

use App\Models\Company;
use App\Models\Entry;
use App\Models\Interview;
use App\Models\LanguageRequirementWork;
use App\Models\Offer;
use App\Models\PassionWork;
use App\Models\Result;
use App\Models\Work;
use App\Repositories\Contracts\WorkRepositoryInterface;
use Carbon\Carbon;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Support\Facades\DB;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class WorkRepository extends BaseRepository implements WorkRepositoryInterface
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->scopeQuery(function ($query) {
            $type = Auth::user()->type;
            if ($type == COMPANY) {
                return $query->where('works.' . Work::USER_ID, Auth::user()->id);
            }

            if ($type == HR || $type == HR_MANAGER) {
                return $query->where(Work::STATUS, WORK_STATUS_RECRUITING);
            }

            return $query;
        });

    }

    /**
     * Instantiate model
     *
     * @param Work $model
     */

    public function model()
    {
        return Work::class;
    }

    public function list($request)
    {
        $user = Auth::user();
        $data = Common::searchWork($request, $this);
        if ($request->has('field') && $request->input('field')) {
            if ($request->input('field') == 'status') {
                $data = Common::sortArrayText($data, 'works.status', HRS_STATUS_TEXTS, $request->input('sort_by') == 'asc');
            } elseif ($request->input('field') == 'company_name') {
                $listCompany = Company::query()->pluck('company_name', 'id')->toArray();
                $data = Common::sortArrayText($data, 'company_id', $listCompany, $request->input('sort_by') == 'asc');
            } else {
                $data = Common::sortBy($request, $data);
            }
        } else {
            $data = $data->orderBy('id', 'desc');
        }
        $dataWorks = Common::pagination($request, $data);
        $dataConvertWork = [];
        $arrayId = $dataWorks->pluck('id')->toArray();
        $checkOnGoingJob = Common::onGoingJob(Auth::user(), $arrayId, 'work');
        $checkResultWork = $this->checkResultWork($arrayId);
        foreach ($dataWorks as $key => $dataWork) {
            $dataConvertWork[$key] = $dataWork;
            $dataConvertWork[$key]['list_disabled_hrs'] = $this->getListHrsForWork($user, $dataWork->id);
            $dataConvertWork[$key]['on_job'] = $this->getOnGoingJob($dataWork, $checkOnGoingJob, $checkResultWork);
        }
        return $dataWorks->setCollection(collect($dataConvertWork));
    }

    public function create(array $attributes)
    {
        $user = Auth::user();
        if (!$attributes['company_id'] && $user->type == COMPANY) {
            $attributes ['company_id'] = $user->company->id ?: 0;
            $attributes['user_id'] = Auth::id();
        } else {
            $company = Company::query()->find($attributes['company_id']);
            $attributes['user_id'] = $company->user_id;
        }
        $attributes['status'] = self::getStatusPeriod($attributes);;
        $attributes['created_by'] = Auth::id();
        $attributes['updated_by'] = Auth::id();
        $result = parent::create($attributes);
        //language
        if ($attributes['language_requirements']) {
            $this->addRelationHasMany(LanguageRequirementWork::class, $attributes['language_requirements'],
                $result->id, 'language_requirement_id');
        }
        if (isset($attributes['passions'])) {
            $this->addRelationHasMany(PassionWork::class, $attributes['passions'], $result->id, 'passion_id');
        }
        $data = $this->with(['languageRequirements', 'passion'])->find($result->id);
        return $data;
    }

    public function delete($id)
    {
        $user = Auth::user();
//        $checkDeleteWork = Work::checkDataBeforeDelete($id);
        $checkDataDelete = $this->checkDataDelete($user, $id);
        if ($checkDataDelete) {
            $updateStatus = $this->updateFollowListWorkDelete([$id]);
            return parent::delete($id);
        }
        return false;
    }

    public function update(array $attributes, $id)
    {
        $user = Auth::user();
        if (!$attributes['company_id']) {
            $attributes['company_id'] = $user->company->id ?: 0;
        }
        if ($attributes['application_period_from'] && $attributes['application_period_to']) {
            $attributes['status'] = self::getStatusPeriod($attributes);
        }
        $result = parent::update($attributes, $id);
        //language
        if ($attributes['language_requirements']) {
            $this->addRelationHasMany(LanguageRequirementWork::class, $attributes['language_requirements'],
                $result->id, 'language_requirement_id');
        }
        if (isset($attributes['passions'])) {
            $this->addRelationHasMany(PassionWork::class, $attributes['passions'], $result->id, 'passion_id');
        }
        $data = $this->with(['languageRequirements', 'passion'])->find($result->id);
        return $data;
    }

    /**
     * Get status from time period
     * return int
     */
    private function getStatusPeriod($object): int
    {
        $now = Carbon::now()->format('Y-m-d');
        if ($object['application_period_from'] <= $now &&
            $object['application_period_to'] >= $now) {
            return WORK_STATUS_RECRUITING;
        }
        return WORK_STATUS_OUT_OF_RECRUITMENT_PERIOD;
    }

    private function addRelationHasMany($model, $items, $id, $column)
    {
        $model::where('work_id', $id)->delete();
        $array = array();
        foreach ($items as $k => $item) {
            $array[$k]['work_id'] = $id;
            $array[$k][$column] = $item;
        }
        $model::insert($array);
    }

    public function updateStatusWork($request, $id)
    {
        $work = $this->find($id);
        $now = Carbon::now()->format('Y-m-d');

        if ($work->status == WORK_STATUS_RECRUITING && $request['status'] == WORK_STATUS_PAUSED) {
            $work->status = $request['status'];

        } elseif ($work->status == WORK_STATUS_PAUSED && $request['status'] == WORK_STATUS_RECRUITING
            && $work->application_period_from <= $now && $work->application_period_to >= $now) {
            $work->status = $request['status'];
        } else {
            return false;
        }
        $work->save();
        return $work;
    }

    /**
     * @param $id
     * @return array
     */
    public function detail($id)
    {
        $user = Auth::user();
        $work = Work::with(['languageRequirements', 'passion', 'company'])->find($id);
        if (!$work) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.data_does_not_exist'));
        }
        $work['list_disabled_hrs'] = $this->getListHrsForWork($user, $id);
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success', $work);
    }

    private function getListHrsForWork($user, $id)
    {
        $dataHrs = [];
        $listGoingJobWork = Common::getGoingJobWork($user, [$id]);
        if (count($listGoingJobWork) != 0) {
            $dataHrs = array_values(array_unique(array_column($listGoingJobWork[$id], 'hrsid')));
        }
        return $dataHrs;
    }
    private function checkDataDelete($user,$id){
        $checkOnGoingJob = Common::onGoingJob($user, [$id], 'work');
        $checkResultWork = $this->checkResultWork([$id]);
        if (count($checkOnGoingJob) != 0 || count($checkResultWork) != 0) {
            return false;
        }
        return true;
    }
    private function getOnGoingJob($dataWork, $checkOnGoingJob, $checkResultWork){
        if (isset($checkOnGoingJob[$dataWork->id]) || isset($checkResultWork[$dataWork->id]) || $dataWork->status == WORK_STATUS_RECRUITING) {
            return true;
        }
        return false;
    }
    private function checkResultWork($arrayListIWork)
    {
        $dataResult = Result::query()->whereIn('work_id', $arrayListIWork)
            ->whereIn('status_selection', [RESULT_STATUS_SELECTION_OFFER, RESULT_STATUS_SELECTION_OFFER_CONFIRM])
            ->get()->keyBy('work_id')->toArray();
        return $dataResult;
    }

    private function updateFollowListWorkDelete($arrayListIHr){
        $entry = Entry::query()->whereIn('work_id', $arrayListIHr)->update(['display' => 'delete']);
        $offer = Offer::query()->whereIn('work_id', $arrayListIHr)->update(['display' => 'delete']);
        $entry = Interview::query()->whereIn('work_id', $arrayListIHr)->update(['display' => 'off']);
        $entry = Result::query()->whereIn('work_id', $arrayListIHr)->update(['display' => 'off']);
    }
}
