<?php

namespace Repository;

use App\Models\JobInfo;
use App\Repositories\Contracts\JobInfoRepositoryInterface;
use Illuminate\Foundation\Application;

class JobInfoRepository extends BaseRepository implements JobInfoRepositoryInterface
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Instantiate model
     *
     * @param JobInfo $model
     */

    public function model()
    {
        return JobInfo::class;
    }
}
