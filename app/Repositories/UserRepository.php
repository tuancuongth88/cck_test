<?php


namespace App\Repositories;


use App\Http\Resources\BaseResource;
use App\Http\Resources\UserResource;
use App\Jobs\NotificationDistributionJob;
use App\Mail\AlertStatusAccountMail;
use App\Mail\StopUserMail;
use App\Models\Company;
use App\Models\Entry;
use App\Models\HR;
use App\Models\HrOrganization;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Role;
use App\Models\User;
use App\Models\Work;
use App\Notifications\DistributionNotification;
use App\Notifications\EntryConfirmNotification;
use App\Notifications\EntryDeclineNotification;
use App\Notifications\EntryRejectionNotification;
use App\Notifications\EntryRequestingNotification;
use App\Notifications\RemindAccountNotification;
use App\Notifications\RemindOnJobNotification;
use App\Repositories\Contracts\UserRepositoryInterface;
use Carbon\Carbon;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Repository\BaseRepository;
use Repository\CompanyRepository;
use Repository\EntryRepository;
use Repository\HrOrganizationRepository;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $companyRepository;
    protected $hrOrganizationRepository;

    public function __construct(Application $app, CompanyRepository $companyRepository, HrOrganizationRepository $hrOrganizationRepository)
    {
        $this->companyRepository = $companyRepository;
        $this->hrOrganizationRepository = $hrOrganizationRepository;
        parent::__construct($app);
    }

    public function model()
    {
        return User::class;
    }

    /**
     * @return mixed|void
     */
    public function getAll($request)
    {

        return $this->model->select([
            'users.*',
            'roles.display_name as display_name',
        ])->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->when($request->get('search'), function ($query) use ($request) {
                return $query->where('roles.display_name', 'like', "%{$request->search}%")
                    ->orWhere('users.name', 'like', "%{$request->search}%")
                    ->orWhere('users.email', 'like', "%{$request->search}%");
            })->when($request->get('order_column'), function ($query) use ($request) {
                return $query->orderBy($request->order_column, $request->get('order_type') == 'ASC' ? 'ASC' : 'DESC');
            }, function ($query) {
                return $query->orderBy('id', 'desc');
            })->paginate($request->per_page);
    }


    public function create(array $attributes)
    {
        return parent::create($attributes);
    }

    public function update(array $attributes, $id)
    {
        return parent::update($attributes, $id);
    }

    public function updateStatusCompany($request)
    {
        return $this->register($request, Company::class, $request['company_id'], trans('messages.mes.subject_mail_confirm_account_company'), 'company');
    }

    public function updateStatusHR($request)
    {
        return $this->register($request, HrOrganization::class, $request['hr_id'], trans('messages.mes.subject_mail_confirm_account_hr'), 'hrOrganization');
    }

    private function register($request, $model, $id, $subject, $relation = null)
    {
        try {
            $dataInput = $request->all();
            $data[$model::STATUS] = $dataInput['status'];
            if ($dataInput['status'] == CONFIRM && $model::find($id)->user_id) {
                $user = $this->model->find($model::find($id)->user_id);
                $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $password = substr(str_shuffle($characters), 0, 12);
                $user->password = $password;
                $user->save();
                $this->sendEmailForUser($model, $data, $id, $subject, $password);
                return ResponseService::responseJson(CODE_SUCCESS, [
                    'message' => trans('messages.mes.update_success')
                ]);
            }
            if ($dataInput['status'] == CONFIRM) {
                $mail = $model::find($id)->mail_address;
                if (!$mail) {
                    return ResponseService::responseJsonError(CODE_ERROR_SERVER, trans('messages.email_not_exist'));
                }
                $checkUser = $this->model->where(User::MAIL_ADDRESS, $mail)->first();
                if ($checkUser) {
                    return ResponseService::responseJsonError(CODE_ERROR_SERVER, trans('errors.user_has_exist'));
                }
                if ($model == Company::class) {
                    $user = $this->registerAccount(array_merge($dataInput, ['type' => COMPANY]), $mail);
                } else {
                    $user = $this->registerAccount(array_merge($dataInput, ['type' => HR]), $mail);
                }
                $data[$model::USER_ID] = $user['profile']['id'];
                $this->sendEmailForUser($model, $data, $id, $subject, $user['password']);
                $userInfo = User::query()->with($relation)->find($user['profile']['id']);
                return ResponseService::responseJson(CODE_SUCCESS, [
                    'profile' => new UserResource($userInfo)
                ]);
            }
            if ($dataInput['status'] == REJECT) {
                $this->sendEmailForUser($model, $data, $id,  __('api.subject_reject'));
                return ResponseService::responseJson(CODE_SUCCESS, [
                    'message' => trans('messages.mail_reject')
                ]);
            }
            if ($dataInput['status'] == STOP_USING) {
                $listId = null;
                $type = 'hrs';
                $company = $model::findOrFail($id);
                $user =  $this->model->find($company->user_id);
                if ($user){
                    if ($model == Company::class){
                        $listId = Work::query()->where('user_id', $user->id);
                    }
                    if ($model == HrOrganization::class){
                        $listId = HR::query()->where('user_id', $user->id);
                    }
                    $listId = $listId->get()->pluck('id')->toArray();
                    $statusStop = Common::onGoingJob($user, $listId, $type);
                    if (count($statusStop) > 0){
                        return ResponseService::responseJsonError(HTTP_UNPROCESSABLE_ENTITY, __('api.cannot_stop_status_user'));
                    }
                }
                $this->sendEmailForUser($model, $data, $id, __('api.subject_stop_user'));
                return ResponseService::responseJson(CODE_SUCCESS, [
                    'message' => trans('messages.mail_stop')
                ]);
            }
            return ResponseService::responseJson(CODE_SUCCESS, [
                'message' => trans('messages.mes.update_success')
            ]);
        } catch (\Exception $exception) {
            Log::error($exception);
            return ResponseService::responseJsonError(CODE_ERROR_SERVER, $exception->getMessage());
        }
    }

    public function registerAccount($data, $mail)
    {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $data = [
            'login_id' => rand(11111111, 99999999),
            'mail_address' => $mail,
            'password' => substr(str_shuffle($characters), 0, 12),
            'type' => $data['type'],
            'status' => $data['status']
        ];
        $user = User::create($data);
        $result['profile'] = $user;
        $result['password'] = $data['password'];
        return $result;
    }

    public function sendEmailForUser($model, $attributes, $id, $subject, $password = null)
    {

        $data = $model::findOrFail($id);
        $data->update($attributes);
        $user = $this->model->where('mail_address', $data->mail_address)->first();
        if ($user) {
            $user->status = $attributes['status'];
            $user->save();
        }
        $result['mail_address'] = $data->mail_address;
        $result['subject'] = $subject;
        $result['date'] = Carbon::now()->format('Y年m月d日 H:m') ;
        $result['user_type'] = $model;
        if ($model == Company::class) {
            $result['name'] = $data->company_name_jp;
        } else {
            $result['name'] = $data->corporate_name_ja;
        }
        if ($attributes['status'] == CONFIRM) {
            $result['password'] = $password;
            $result['user_type'] = $model;
            Mail::to($data->mail_address)
                ->send(new AlertStatusAccountMail($result, CONFIRM));
        }
        if ($attributes['status'] == REJECT) {
            Mail::to($data->mail_address)
                ->send(new AlertStatusAccountMail($result, REJECT));
        }
        if ($attributes['status'] == STOP_USING) {
            Mail::to($data->mail_address)
                ->send(new AlertStatusAccountMail($result, STOP_USING));
        }
    }

    public function getNotify($request)
    {
        $user = Auth::user();
        $data = Notification::query()
            ->where('notifiable_id', $user->id)
            ->where('type', '<>',DistributionNotification::class)
            ->orderByDesc('created_at');
        $data = Common::pagination($request, $data);
        return ResponseService::responseJson(CODE_SUCCESS, BaseResource::collection($data));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function onGoingJob(Request $request)
    {
        $user = Auth::user();
        $dataFull = [];
        $perPage = $request->per_page ? $request->per_page : 5;
        $page = $request->page ? $request->page : 1;
        if (!$page) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', 'error');
        }
        $listDataOffer = Offer::select("offers.id", "hrs.id as hrsid", 'hrs.full_name', 'hrs.full_name_ja',
            "offers.status", "offers.request_date", "works.id as workdid", "works.title", "companies.company_name", "companies.company_name_jp", "interviews.step")
            ->join('hrs', 'hrs.id', '=', "offers.hr_id")
            ->join('works', 'works.id', '=', "offers.work_id")
            ->join('companies', 'companies.id', '=', "works.company_id")
            ->leftJoin('interviews', function ($interview) {
                $interview->on('interviews.hr_id', '=', 'hrs.id')
                    ->on('works.id', '=', 'interviews.work_id')
                    ->where('interviews.display','!=','off');
            })
            ->whereIn('offers.display', ['on', 'off'])
            ->orderBy('offers.id', 'desc');
        $listDataOffer = Common::getDataForPermisstion($listDataOffer, $user);
        $listDataOffer = $listDataOffer->limit(rand(1700,2200))->get();

        //entry
        $listDataEntry = Entry::select("entries.id", "hrs.id as hrsid", 'hrs.full_name', 'hrs.full_name_ja',
            "entries.status", "works.id as workdid", "entries.request_date", "works.title", "entries.code", "companies.company_name", "companies.company_name_jp", "interviews.step")
            ->join('hrs', 'hrs.id', '=', "entries.hr_id")
            ->join('works', 'works.id', '=', "entries.work_id")
            ->join('companies', 'companies.id', '=', "works.company_id")
            ->leftJoin('interviews', function ($interview) {
                $interview->on('interviews.hr_id', '=', 'hrs.id')
                    ->on('works.id', '=', 'interviews.work_id')
                    ->where('interviews.display','!=','off');
            })
            ->whereIn('entries.display', ['on', 'off'])
            ->orderBy('entries.id', 'desc');
        $listDataEntry = Common::getDataForPermisstion($listDataEntry, $user);
        $listDataEntry = $listDataEntry->limit(rand(1700,2200))->get();


        $totalRecord = count($listDataOffer) + count($listDataEntry);
        $listDatas = collect(array_merge($listDataOffer->toArray(), $listDataEntry->toArray()))->sortByDesc('request_date')->forPage($page, $perPage);
        foreach ($listDatas as $listData) {
            $dataFull[] = [
                'id' => $listData['id'],
                'date' => $listData['request_date'],
//                'dateJa' => Carbon::parse($listData['request_date'])->format('Y年m月d日') . ' (' . Carbon::parse($listData['request_date'])->isoFormat('ddd') . ')',
                'dateJa' => Carbon::parse($listData['request_date'])->format('Y年m月d日'),//remove day on week
                'id_hrs' => $listData['hrsid'],
                'full_name' => $listData['full_name'],
                'full_name_ja' => $listData['full_name_ja'],
                'id_job' => $listData['workdid'],
                'job_name' => $listData['title'],
                'company_name' => $listData['company_name'],
                'company_name_ja' => $listData['company_name_jp'],
                'occupation' => isset($listData['code']) ? 'entry' : 'offer',
                'occupation_ja' => isset($listData['code']) ? 'エントリー' : 'オファー',
                'to_link' => $this->getNextDetailPage($listData)
            ];
        }
        $data['result'] = $dataFull;
        $data['pagination'] = $this->getPaginate($totalRecord, $perPage, $page);
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success', $data);
    }

    /**
     * @return array
     */
    public function unreadMessages()
    {
        $user = Auth::user();
        $data = [];
        $data['mesOtherList'] = Notification::query()
            ->where('notifiable_id', $user->id)
            ->where('type', DistributionNotification::class)
            ->whereNull('read_at')
            ->count();
        $data['mesNotification'] = Notification::query()
            ->where('notifiable_id', $user->id)
            ->where('type', '<>',DistributionNotification::class)
            ->whereNull('read_at')->count();
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success', $data);
    }

    private function totalRecord($user, $textCount, $pageOffer = null, $pageEntry = null)
    {
        $totalRecord = 0;
        $offerCount = Offer::select("offers.id", "hrs.id as hrsid", 'hrs.full_name', 'hrs.full_name_ja',
            "offers.status", "offers.request_date", "works.id as workdid", "works.title", "companies.company_name", "companies.company_name_jp")
            ->join('hrs', 'hrs.id', '=', "offers.hr_id")
            ->join('works', 'works.id', '=', "offers.work_id")
            ->join('companies', 'companies.id', '=', "works.company_id")
            ->whereIn('offers.display', ['on', 'off'])
            ->whereNotIn('offers.status', [OFFER_STATUS_DECLINE]);
        $offerCount = Common::getDataForPermisstion($offerCount, $user);
        if ($textCount == 'offer') {
            $pageOfferConvert = $this->convertDataToRequest($pageOffer['page'], $pageOffer['per_page']);
            $offerCount = Common::pagination($pageOfferConvert, $offerCount);
        }
        $offerCount = $offerCount->count();
        if ($pageOffer) {
            return $offerCount;
        }
        $entryCount = Entry::select("entries.id", "hrs.id as hrsid", 'hrs.full_name', 'hrs.full_name_ja',
            "entries.status", "works.id as workdid", "entries.request_date", "works.title", "entries.code", "companies.company_name", "companies.company_name_jp")
            ->join('hrs', 'hrs.id', '=', "entries.hr_id")
            ->join('works', 'works.id', '=', "entries.work_id")
            ->join('companies', 'companies.id', '=', "works.company_id")
            ->whereIn('entries.display', ['on', 'off'])
            ->whereNotIn('entries.status', [ENTRY_STATUS_DECLINE, ENTRY_STATUS_REJECT]);
        $entryCount = Common::getDataForPermisstion($entryCount, $user);
        if ($textCount == 'entry') {
            $pageEntryConvert = $this->convertDataToRequest($pageEntry['page'], $pageEntry['per_page']);
            $entryCount = Common::pagination($pageEntryConvert, $entryCount);
        }
        $entryCount = $entryCount->count();
        if ($pageEntry) {
            return $entryCount;
        }
        $totalRecord = $offerCount + $entryCount;
        return $totalRecord;
    }

    private function getPerPage($perPage, $page, $user)
    {
        $pagination = [];
        $pageOffer = (int)ceil($perPage / 2);
        $pageEntry = (int)floor($perPage / 2);
        $paginationOffer = [
            'page' => (int)$page,
            'per_page' => (int)$pageOffer
        ];
        $paginationEntry = [
            'page' => (int)$page,
            'per_page' => (int)$pageEntry
        ];
        $offerCount = $this->totalRecord($user, 'offer', $paginationOffer);
        $entryCount = $this->totalRecord($user, 'entry', null, $paginationEntry);
        if ($offerCount == 0) {
            $pagination['per_page_offer'] = 0;
            $pagination['per_page_entry'] = $perPage;
            return $pagination;
        }
        if ($entryCount == 0) {
            $pagination['per_page_offer'] = $perPage;
            $pagination['per_page_entry'] = 0;
            return $pagination;
        }
        if ($offerCount < $pageOffer) {
            $pagination['per_page_offer'] = $offerCount;
            $pagination['per_page_entry'] = $pageEntry + ($pageOffer - $offerCount);
            return $pagination;
        }
        if ($entryCount < $pageEntry) {

            $pagination['per_page_offer'] = $pageOffer + ($pageEntry - $entryCount);
            $pagination['per_page_entry'] = $entryCount;
            return $pagination;
        }
        $pagination['per_page_offer'] = $pageOffer;
        $pagination['per_page_entry'] = $pageEntry;
        return $pagination;
    }

    private function convertDataToRequest($page, $perPage)
    {
        $pageConvert = \Illuminate\Support\Facades\Request::merge(['page' => $page, 'per_page' => $perPage]);
        return $pageConvert;
    }

    private function getPaginate($totalRecord, $perPage, $page)
    {
        $paginate = [];
        $paginate["display"] = $perPage;
        $paginate["total_records"] = (int)$totalRecord;
        $paginate["per_page"] = (int)$perPage;
        $paginate["current_page"] = (int)$page;
        $paginate["total_pages"] = (int)ceil($totalRecord / $perPage);
        return $paginate;

    }

    private function getNextDetailPage($listData)
    {
        $step = $listData['step'];
        $toLink = null;
        if (!$step) {
            $toLink = isset($listData['code']) ? NAV_TAB_ENTRY : NAV_TAB_OFFER;
        } elseif ($step == INTERVIEW_TABLE_STEP_INTERVIEW) {
            $toLink = NAV_TAB_INTERVIEW;
        } else {
            $toLink = NAV_TAB_RESULT;
        }
        return $toLink;
    }

}
