<?php

namespace Repository;

use App\Models\UploadFile;
use App\Repositories\Contracts\UploadFileRepositoryInterface;
use Carbon\Carbon;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadFileRepository extends BaseRepository implements UploadFileRepositoryInterface
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Instantiate model
     *
     * @param UploadFile $model
     */

    public function model()
    {
        return UploadFile::class;
    }

    public function upload($request)
    {
        $file = $request->hasFile('file') ? $request->file('file') : '';
        $fileModel = $request->get('model_file');
        $type = $request->get('type') ?? '';
        if($file) {
            $path = $fileModel . '/' . Carbon::now()->format('Ymd');
            $fileName = md5(Carbon::now()->format('YmdHis')) . $file->getClientOriginalName();

            return UploadFile::create([
                'file_path' => 'storage/' . $file->storeAs($path, $fileName),
                'file_name' => $file->getClientOriginalName(),
                "file_extension" => $file->getClientOriginalExtension(),
                "file_size" => $file->getSize(),
                'file_model' => $fileModel,
                'item_type' => $type
            ]);
        }
    }

    public function update(array $attributes, $id)
    {
        return parent::update($attributes, $id);
    }

    public function downloadFidelity($id){
        $file = UploadFile::find($id);
        if (!$file) return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.data_does_not_exist'));
        $convertNameFile = Str::replace('storage/','',$file->file_path);
        return response()->download(storage_path('app/public/'.$file->file_path));
    }
}
