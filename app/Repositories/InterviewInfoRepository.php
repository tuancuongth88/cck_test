<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace Repository;

use App\Models\InterviewInfo;
use App\Repositories\Contracts\InterviewInfoRepositoryInterface;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class InterviewInfoRepository extends BaseRepository implements InterviewInfoRepositoryInterface
{

     public function __construct(Application $app)
     {
         parent::__construct($app);

     }

    /**
       * Instantiate model
       *
       * @param InterviewInfo $model
       */

    public function model()
    {
        return InterviewInfo::class;
    }


}
