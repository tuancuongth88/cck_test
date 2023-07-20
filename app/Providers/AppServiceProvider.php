<?php

namespace App\Providers;


use App\Models\Setting;
use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Repositories\Contracts\CityRepositoryInterface;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use App\Repositories\Contracts\HrOrganizationRepositoryInterface;
use App\Repositories\Contracts\JobInfoRepositoryInterface;
use App\Repositories\Contracts\JobTypeRepositoryInterface;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Repositories\Contracts\UploadFileRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\UserFavoriteRepositoryInterface;
use App\Repositories\Contracts\WorkRepositoryInterface;
use App\Repositories\Contracts\EntryRepositoryInterface;
use App\Repositories\Contracts\HRRepositoryInterface;
use App\Repositories\Contracts\ResultRepositoryInterface;
use App\Repositories\Contracts\OfferRepositoryInterface;
use App\Repositories\Contracts\InterviewRepositoryInterface;
use App\Repositories\Contracts\InterviewInfoRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use Repository\BaseRepository;
use Repository\AuthRepository;
use Repository\CityRepository;
use Repository\CompanyRepository;
use Repository\EtcMasterRepository;
use Repository\DegitachoMasterRepository;
use Repository\HrOrganizationRepository;
use Repository\JobTypeRepository;
use Repository\NotificationRepository;
use Repository\UploadFileRepository;
use Repository\VehicleMasterRepository;
use Repository\MasterManagementRepository;
use Repository\JobInfoRepository;
use Repository\UserFavoriteRepository;
use Repository\WorkRepository;
use Repository\EntryRepository;
use Repository\HRRepository;
use Repository\ResultRepository;
use Repository\OfferRepository;
use Repository\InterviewRepository;
use Repository\InterviewInfoRepository;

use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(JobTypeRepositoryInterface::class, JobTypeRepository::class);
        $this->app->bind(JobInfoRepositoryInterface::class, JobInfoRepository::class);
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(HrOrganizationRepositoryInterface::class, HrOrganizationRepository::class);
        $this->app->bind(UploadFileRepositoryInterface::class, UploadFileRepository::class);
        $this->app->bind(UserFavoriteRepositoryInterface::class, UserFavoriteRepository::class);
        $this->app->bind(WorkRepositoryInterface::class, WorkRepository::class);
        $this->app->bind(EntryRepositoryInterface::class, EntryRepository::class);
        $this->app->bind(HRRepositoryInterface::class, HRRepository::class);
        $this->app->bind(ResultRepositoryInterface::class, ResultRepository::class);
        $this->app->bind(OfferRepositoryInterface::class, OfferRepository::class);
        $this->app->bind(InterviewRepositoryInterface::class, InterviewRepository::class);
        $this->app->bind(InterviewInfoRepositoryInterface::class, InterviewInfoRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);

        //Customer
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
