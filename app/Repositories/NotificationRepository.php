<?php
/**
 * Created by VeHo.
 * Year: 2023-07-04
 */

namespace Repository;

use App\Http\Resources\BaseResource;
use App\Jobs\CacheShiftList;
use App\Jobs\NotificationDistributionJob;
use App\Models\Notification;
use App\Models\UploadFile;
use App\Notifications\DistributionNotification;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Carbon\Carbon;
use Helper\Common;
use Helper\ResponseService;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{

    public function __construct(Application $app)
    {
        parent::__construct($app);

    }

    /**
     * Instantiate model
     *
     * @param Notification $model
     */

    public function model()
    {
        return Notification::class;
    }

    public function create(array $attributes)
    {
        $authUser = Auth::user();
        $image = null;
        if(isset($attributes['image_id']))
            $image = UploadFile::find($attributes['image_id']);
        $urlImage = $image ? $image->file_path : null;
        $reloadCache = NotificationDistributionJob::dispatch($authUser, $attributes['title'], $attributes['text'], $urlImage);

    }

    public function update(array $attributes, $id)
    {
        $attributes['read_at'] = Carbon::now();
        return parent::update($attributes, $id); // TODO: Change the autogenerated stub
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function otherList($request)
    {
        $user = Auth::user();
        $data = Notification::query()
            ->where('notifiable_id', $user->id)
            ->where('type', DistributionNotification::class)
            ->orderByDesc('created_at');
        $data = Common::pagination($request, $data);
        return ResponseService::responseJson(CODE_SUCCESS, BaseResource::collection($data));
    }

    /**
     * @param $id
     * @return array
     */
    public function detail($id)
    {
        $authLogin = Auth::user();
        $notification = Notification::query()->where('id', $id)->where('notifiable_id', $authLogin->id)->first();
        if (!$notification) return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.data_does_not_exist'));
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success',$notification);
    }

}
