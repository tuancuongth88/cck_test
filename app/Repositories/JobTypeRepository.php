<?php

namespace Repository;

use App\Models\JobType;
use App\Repositories\Contracts\JobTypeRepositoryInterface;
use Illuminate\Foundation\Application;

class JobTypeRepository extends BaseRepository implements JobTypeRepositoryInterface
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Instantiate model
     *
     * @param JobType $model
     */

    public function model()
    {
        return JobType::class;
    }

    public function getAll($type)
    {
        return JobType::query()->with('jobInfo:id,job_type_id,name_ja,name_en')
            ->select('id', 'name_ja', 'name_en', 'type')
            ->where('type', $type)
            ->get();
    }
}
