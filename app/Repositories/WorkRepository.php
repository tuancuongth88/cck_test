<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace Repository;

use App\Models\Company;
use App\Models\LanguageRequirementWork;
use App\Models\PassionWork;
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
         $this->scopeQuery(function ($query)
         {
             $type = Auth::user()->type;
             if ($type == COMPANY)
             {
                 return $query->where('works.'.Work::USER_ID, Auth::user()->id);
             }

             if ($type == HR || $type == HR_MANAGER)
             {
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
        $data = Common::searchWork($request, $this);
        if ($request->has('field') && $request->input('field')) {
            if ($request->input('field') == 'status') {
                $data = Common::sortArrayText($data, 'works.status', HRS_STATUS_TEXTS, $request->input('sort_by') == 'asc');
            }
            elseif ($request->input('field') == 'company_name'){
                $listCompany = Company::query()->pluck('company_name', 'id')->toArray();
                $data = Common::sortArrayText($data, 'company_id', $listCompany, $request->input('sort_by') == 'asc');
            }
            else {
                $data = $data->orderBy('id', 'desc');
            }
        }
        return Common::pagination($request, $data);
    }

    public function create(array $attributes)
    {
        $user = Auth::user();
        if (!$attributes['company_id'] && $user->type == COMPANY){
            $attributes ['company_id'] = $user->company->id ?: 0;
            $attributes['user_id'] = Auth::id();
        }else{
            $company = Company::query()->find($attributes['company_id']);
            $attributes['user_id'] = $company->user_id;
        }
        $attributes['status'] = self::getStatusPeriod($attributes);;
        $attributes['created_by'] = Auth::id();
        $attributes['updated_by'] = Auth::id();
        $result = parent::create($attributes);
        //language
        if ($attributes['language_requirements'])
        {
            $this->addRelationHasMany(LanguageRequirementWork::class, $attributes['language_requirements'],
                $result->id,'language_requirement_id');
        }
        if (isset($attributes['passions']))
        {
            $this->addRelationHasMany(PassionWork::class, $attributes['passions'], $result->id, 'passion_id');
        }
        $data = $this->with(['languageRequirements', 'passion'])->find($result->id);
        return $data;
    }

    public function delete($id)
    {
        $checkDeleteWork = Work::checkDataBeforeDelete($id);
        if ($checkDeleteWork)
        {
            return parent::delete($id);
        }
        return false;
    }

    public function update(array $attributes, $id)
    {
        $user = Auth::user();
        if (!$attributes['company_id']){
            $attributes['company_id'] = $user->company->id ?: 0;
        }
        $attributes['user_id'] = Auth::id();
        if ($attributes['application_period_from'] && $attributes['application_period_to']){
            $attributes['status'] = self::getStatusPeriod($attributes);
        }
        $result = parent::update($attributes, $id);
        //language
        if ($attributes['language_requirements'])
        {
            $this->addRelationHasMany(LanguageRequirementWork::class, $attributes['language_requirements'],
                $result->id,'language_requirement_id');
        }
        if (isset($attributes['passions']))
        {
            $this->addRelationHasMany(PassionWork::class, $attributes['passions'], $result->id, 'passion_id');
        }
        $data = $this->with(['languageRequirements', 'passion'])->find($result->id);
        return  $data;
    }

    /**
     * Get status from time period
     * return int
     */
    private function getStatusPeriod($object): int
    {
        $now = Carbon::now()->format('Y-m-d');
        if ($object['application_period_from'] <= $now &&
            $object['application_period_to'] >= $now){
            return WORK_STATUS_RECRUITING;
        }
        return WORK_STATUS_OUT_OF_RECRUITMENT_PERIOD;
    }

    private function addRelationHasMany($model, $items, $id, $column)
    {
        $model::where('work_id', $id)->delete();
        $array = array();
        foreach (json_decode($items) as $k => $item){
            $array[$k]['work_id'] = $id;
            $array[$k][$column] = $item;
        }
        $model::insert($array);
    }

    public function updateStatusWork($request, $id){
        $work = $this->find($id);
        $work->status = $request['status'];
        $work->save();
        return $work;
    }
}
