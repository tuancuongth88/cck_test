<?php

namespace Repository;

use App\Jobs\RemindAccountJob;
use App\Models\HR;
use App\Models\HrOrganization;
use App\Models\UploadFile;
use App\Models\User;
use App\Notifications\SendNotification;
use App\Repositories\Contracts\HrOrganizationRepositoryInterface;
use Helper\Common;
use Illuminate\Foundation\Application;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class HrOrganizationRepository extends BaseRepository implements HrOrganizationRepositoryInterface
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Instantiate model
     *
     * @param HrOrganization $model
     */

    public function model()
    {
        return HrOrganization::class;
    }


    public function create(array $attributes)
    {
        try {
            if($attributes['is_create']) {
                $attributes['status'] = EXAMINATION_PENDING;
                $data = parent::create($attributes);
                UploadFile::query()
                    ->where('id',$attributes['certificate_file_id'])
                    ->update(['file_model' => HrOrganization::class]);
                RemindAccountJob::dispatch(HR_MANAGER, HrOrganization::class, $data->id);
                return $data;
            }
        }catch (\Exception $exception)
        {
            Log::error($exception);
            return false;
        }
    }

    public function getAll($request)
    {
        $data = $this->model;
        if ($request->has('field') && $request->field) {
            $data = Common::sortBy($request, $data);
        }else{
            $data = $data->orderBy('id', 'desc');
        }
        $data = Common::pagination($request, $data);

        return $data;
    }

    public function getAllName()
    {
        if(Auth::user()->type == \HR) {
            return $this->model->where('status', CONFIRM)
                ->where(HrOrganization::USER_ID, Auth::id())
                ->get();
        }

        return $this->model->where('status', CONFIRM)
            ->get();
    }

    public function getDetail($id)
    {
        return $this->with('file')->find($id);
    }
}
