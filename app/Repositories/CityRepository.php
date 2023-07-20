<?php

namespace Repository;

use App\Models\City;
use App\Repositories\Contracts\CityRepositoryInterface;
use Illuminate\Foundation\Application;

class CityRepository extends BaseRepository implements CityRepositoryInterface
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Instantiate model
     *
     * @param City $model
     */

    public function model()
    {
        return City::class;
    }

    public function getAll()
    {
        return $this->model->select('id','name_en', 'name_ja')->get();
    }
}
