<?php


namespace App\Repositories;


use App\Http\Resources\BaseResource;
use App\Http\Resources\UserResource;
use App\Mail\AlertStatusAccountMail;
use App\Mail\StopUserMail;
use App\Models\Company;
use App\Models\HrOrganization;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use App\Notifications\DistributionNotification;
use App\Notifications\EntryConfirmNotification;
use App\Notifications\EntryDeclineNotification;
use App\Notifications\EntryRejectionNotification;
use App\Notifications\EntryRequestingNotification;
use App\Notifications\RemindAccountNotification;
use App\Notifications\RemindOnJobNotification;
use App\Repositories\Contracts\UserRepositoryInterface;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Foundation\Application;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Repository\BaseRepository;
use Repository\CompanyRepository;
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

    public function updateStatusCompany($request){
        return $this->register($request, Company::class, $request['company_id'], trans('messages.mes.subject_mail_confirm_account_company'),'company');
    }

    public function updateStatusHR($request){
        return $this->register($request, HrOrganization::class, $request['hr_id'], trans('messages.mes.subject_mail_confirm_account_hr'),'hrOrganization');
    }

    private function register($request, $model, $id, $subject, $relation = null){
        try {
            $dataInput = $request->all();
            $data[$model::STATUS] = $dataInput['status'];
            if ($dataInput['status'] == CONFIRM && $model::find($id)->user_id){
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
                if (!$mail){
                    return ResponseService::responseJsonError(CODE_ERROR_SERVER, trans('messages.email_not_exist'));
                }
                $checkUser = $this->model->where(User::MAIL_ADDRESS, $mail)->first();
                if ($checkUser){
                    return ResponseService::responseJsonError(CODE_ERROR_SERVER, trans('errors.user_has_exist'));
                }
                if($model == Company::class) {
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
                $this->sendEmailForUser($model, $data, $id, 'Reject account');
                return ResponseService::responseJson(CODE_SUCCESS, [
                    'message' => trans('messages.mail_reject')
                ]);
            }
            if ($dataInput['status'] == STOP_USING) {
                $this->sendEmailForUser($model, $data, $id, 'Stop account');
                return ResponseService::responseJson(CODE_SUCCESS, [
                    'message' => trans('messages.mail_stop')
                ]);
            }
            return ResponseService::responseJson(CODE_SUCCESS, [
                'message' => trans('messages.mes.update_success')
            ]);
        }catch (\Exception $exception){
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

    public function sendEmailForUser($model, $attributes, $id, $subject,$password = null){

        $data = $model::findOrFail($id);
        $data->update($attributes);
        $user = $this->model->where('mail_address', $data->mail_address)->first();
        if ($user){
            $user->status = $attributes['status'];
            $user->save();
        }
        $result['mail_address'] = $data->mail_address;
        $result['subject'] = $subject;
        if($attributes['status'] == CONFIRM) {
            $result['password'] = $password;
            if ($model == Company::class)
            {
                $result['name'] = $data->company_name_jp;
            }else{
                $result['name'] = $data->corporate_name_ja;
            }
            Mail::to($data->mail_address)
                ->send(new AlertStatusAccountMail($result, CONFIRM));
        }
        if($attributes['status'] == REJECT) {
            Mail::to($data->mail_address)
                ->send(new AlertStatusAccountMail($result, REJECT));
        }
        if ($attributes['status'] == STOP_USING){
            Mail::to($data->mail_address)
                ->send(new AlertStatusAccountMail($result, STOP_USING));
        }
    }

    public function getNotify($request)
    {
        $user = Auth::user();
        $data = Notification::query()
                            ->where('notifiable_id', $user->id)
                            ->orderByDesc('created_at');
        $data =  Common::pagination($request, $data);
        return ResponseService::responseJson(CODE_SUCCESS, BaseResource::collection($data));
    }
}
