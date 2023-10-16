<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace Repository;

use App\Jobs\NotificationOfferJob;
use App\Jobs\RemindOfferJob;
use App\Http\Requests\OfferRequest;
use App\Models\Interview;
use App\Models\Offer;
use App\Models\User;
use App\Repositories\Contracts\OfferRepositoryInterface;
use Carbon\Carbon;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Support\Facades\Log;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class OfferRepository extends BaseRepository implements OfferRepositoryInterface
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->scopeQuery(function ($query) {
            if (Auth::user()->type == HR) {
                return $query->where('hrs.user_id', Auth::user()->id);
            }
            if (Auth::user()->type == COMPANY) {
                return $query->where('works.user_id', Auth::user()->id);
            }
            return $query->whereIn('display', ['on','stop']);
        });

    }

    /**
     * Instantiate model
     *
     * @param Offer $model
     */

    public function model()
    {
        return Offer::class;
    }

    public function create(array $attributes)
    {
        $user = Auth::user();
        $attributes[Offer::STATUS] = OFFER_STATUS_REQUESTING;
        $attributes[Offer::REQUEST_DATE] = Carbon::now()->format('Y-m-d');
        $attributes[Offer::REMARKS] = isset($attributes[Offer::REMARKS])?$attributes[Offer::REMARKS]:'';
        $checkOfferCreate = $this->checkOfferCreate($user,$attributes);
        if ($checkOfferCreate['status']!='success'){
            return  $checkOfferCreate;
        }
        $offer = Offer::firstOrCreate($attributes);
        NotificationOfferJob::dispatch($user, $offer, OFFER_STATUS_REQUESTING, null);
        return ResponseService::responseData(CODE_SUCCESS,'success','success',$offer);
    }

    /**
     * @param $attributes
     * @param bool $paginate
     * @return mixed
     */
    public function list($attributes, $paginate = true)
    {
        $ids = $attributes['ids'] ?? '';
        $mainTbl = (new Offer())->getTable();
        $items = $this->select("*", "$mainTbl.id", 'hrs.full_name', "$mainTbl.status")
            ->join('hrs', 'hrs.id', '=', "$mainTbl.hr_id")
            ->join('works', 'works.id', '=', "$mainTbl.work_id")
            ->whereNull('hrs.deleted_at')
            ->whereNull('works.deleted_at')
            ->where("$mainTbl.status", "!=", OFFER_STATUS_CONFIRM)
            ->whereIn('offers.display', ['on','stop']);
        if ($ids) {
            $items->whereIn("$mainTbl.id", $ids);
        }
        $items = HRRepository::searchFilter($items, $attributes);
        if (is_object($attributes) && $attributes->has('field') && $attributes->field) {
            $field = $attributes->field;
            $sortBy = $attributes->sort_by;
            if ($field == 'work_title') {
                $attributes->field = "works.title";
            }
            elseif ($field == 'offer_date') {
                $attributes->field = "$mainTbl.request_date";
            }
            elseif ($field == 'full_name') {
                $attributes->field = "hrs.full_name";
            }
            else {
                $attributes->field = "$mainTbl.$field";
            }
            if ($field == 'status') {
                $items = Common::sortArrayText($items, "$mainTbl.$field", OFFER_STATUS_TEXTS, $sortBy == 'asc');
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
                'offer_date' => date('Y-m-d', strtotime($item->request_date)),
                'hr_id' => $item->hr_id,
                'full_name' => $item->full_name,
                'full_name_ja' => $item->full_name_ja,
                'work_id' => $item->work_id,
                'work_title' => $item->title,
                'status_selection' => $item->status,
                'status_selection_name' => OFFER_STATUS_TEXTS[$item->status],
                'display' => $item->display
            ];
        });
        if ($paginate) {
            $items->setCollection(collect($updateItems));
        }
        return $items;
    }

    /** offer_id = 1,2,3,4,5,6;
     * @param OfferRequest $request
     * @return bool
     */
    public function removeOffer(OfferRequest $request)
    {
        try {
            $OfferId = $request->ids;
            $arrayListIdOffer = array_unique($OfferId);
            $updateStatus = Offer::query()->whereIn('id', $arrayListIdOffer)->update([
                'display' => 'delete',
            ]);
            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    /**
     * @param $id
     * @return array|false
     */
    public function detail($id)
    {
        $auLogin = Auth::user();
        $updateItems = [];
        $mainTbl = (new Offer())->getTable();
        $item = $this->select("*", "$mainTbl.id", 'hrs.full_name', "$mainTbl.status")
            ->join('hrs', 'hrs.id', '=', "$mainTbl.hr_id")
            ->join('works', 'works.id', '=', "$mainTbl.work_id")
            ->whereNull('hrs.deleted_at')
            ->whereNull('works.deleted_at')
            ->where("$mainTbl.status", "!=", OFFER_STATUS_CONFIRM)
            ->whereIn('offers.display', ['on','stop'])
            ->where("$mainTbl.id", $id)
            ->first();
        if (!$item) return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.data_does_not_exist'));
        $weekdays = $item->updated_at->isoFormat('ddd');
        $updateItems = [
            'id' => $item->id,
            'offer_date' => date('Y-m-d', strtotime($item->request_date)),
            'hr_id' => $item->hr_id,
            'full_name' => $item->full_name,
            'full_name_ja' => $item->full_name_ja,
            'work_id' => $item->work_id,
            'work_title' => $item->title,
            'status' => $item->status,
            'status_name' => OFFER_STATUS_TEXTS[$item->status],
            'note' => $item->note,
            'display' => $item->display,
            'updating_date_ja' => $item->updated_at->format('Y年m月d日').' (' . $weekdays . ') '
        ];
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success', $updateItems);
    }

    /**
     * @param $attributes
     * @param $id
     * @return mixed
     */
    public function updateStatus($attributes, $id)
    {
        try {
            $authLogin = Auth::user();
            $status = $attributes['status'];
            $note = isset($attributes['note']) ? $attributes['note'] : null;
            $offer = Offer::where('id', $id)->where('display', 'on')->first();
            if (!$offer) return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.data_does_not_exist'));
            $checkUpdateStatus = $this->checkUpdateStatusOffer($authLogin, $offer, $status, $note);
            if ($checkUpdateStatus['code'] != CODE_SUCCESS) {
                return $checkUpdateStatus;
            }
            $updateStatus = $offer->update([
                'status' => $status,
                'note' => $note,
                'display' => $status == OFFER_STATUS_CONFIRM ? 'off' : 'stop'
            ]);
            //tạo mới thông tin ở interview
            if ($status == OFFER_STATUS_CONFIRM && in_array($authLogin->type, [HR, HR_MANAGER, SUPER_ADMIN])) {
                $createInterview = $this->createInterview($offer);
            }
            $sendNotiOffer = NotificationOfferJob::dispatch($authLogin, $offer, $status, $note);
            return ResponseService::responseData(CODE_SUCCESS, 'success', Common::getMessageModal('offer',$status));
        } catch (\Exception $exception) {
            Log::error('updateStatusOffer :' . $exception);
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.data_does_not_exist'));
        }
    }
    private function checkUpdateStatusOffer($authLogin, $offer, $status, $note)
    {
        //check status
        if (in_array($offer->status, [OFFER_STATUS_DECLINE, OFFER_STATUS_CONFIRM])) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.data_does_not_exist'));
        }
        if ($status == OFFER_STATUS_REQUESTING) {
            return self::resp(CODE_NO_ACCESS, trans('api.offer.status.invalid'));
        }
        //check permission role HR
        if ($authLogin->type == HR && $offer->hr->user_id != $authLogin->id) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.mes.permission'));
        }
        //check permission status follow role HR and Company
        if (((in_array($authLogin->type, [HR, HR_MANAGER]) && !in_array($status, [OFFER_STATUS_DECLINE, OFFER_STATUS_CONFIRM])))) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.status_invalid'));
        }
        return ResponseService::responseData(CODE_SUCCESS, 'success', trans('messages.mes.create_success'));
    }

    private function createInterview($offer)
    {
//        Kiểm tra bản ghi đã tồn tại
        $checkTypeGroup = Interview::query()
            ->where('work_id', $offer->work_id)
            ->where('hr_id', $offer->hr_id)
            ->where('status_selection', INTERVIEW_STATUS_SELECTION_OFFER_CONFIRM)
            ->where('display', 'on')
            ->first();
        if ($checkTypeGroup) return true;
        $creareInterView = Interview::create([
            "hr_id" => $offer->hr_id,
            "work_id" => $offer->work_id,
            "code" => null,
            "interview_date" => Carbon::now()->toDateString(),
            "type" => INTERVIEW_TYPE_PRIVATE,
            "status_selection" => INTERVIEW_STATUS_SELECTION_OFFER_CONFIRM,
            "status_interview_adjustment" => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT,
            "remarks" => '',
            "display" => "on",
            "step" => INTERVIEW_TABLE_STEP_INTERVIEW
        ]);
        return true;
    }
    private function checkOfferCreate($user,$attributes){
        $hrId = $attributes['hr_id'];
        $workId = $attributes['work_id'];

        $getListOnGoingJob = Common::getGoingJobWork($user,[$workId]);
        if (count($getListOnGoingJob) == 0) {
            return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
        }
        $listHrinJob = array_unique(array_column($getListOnGoingJob[$workId],'hrsid'));
        if (in_array($hrId,$listHrinJob)){
            return ResponseService::responseData(CODE_NO_ACCESS,'error',trans('api.offer.check.list-hrs.create'));
        }
        return ResponseService::responseData(CODE_SUCCESS,'success','success');
    }
}
