<?php

namespace App\Providers;

use App\Models\HR;
use App\Models\Role;
use App\Models\UserFavorite;
use App\Models\Work;
use App\Policies\FavoritePolicy;
use App\Policies\HrPolicy;
use App\Policies\RolePolicy;
use App\Policies\WorkPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Role::class => RolePolicy::class,
        Work::class => WorkPolicy::class,
        HR::class => HrPolicy::class,
        UserFavorite::class => FavoritePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
