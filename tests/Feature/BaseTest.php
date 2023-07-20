<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\HR;
use App\Models\HrOrganization;
use App\Models\User;
use Tests\TestCase;

class BaseTest extends TestCase
{
    protected $user_admin;
    protected $user_hr_manager;
    protected $user_company_manager;
    protected $user_hr;
    protected $user_company;
    protected $user_login;
    protected $company;
    protected $hr;

    static $runSql = false;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        if (!static::$runSql) {
            $this->artisan('migrate:fresh');
            $this->artisan('db:seed');
        }

        $user_admin_manager = User::where(User::TYPE, SUPER_ADMIN)->first();
        if (!$user_admin_manager) {
            $user_admin_manager = User::factory()->create([User::TYPE => SUPER_ADMIN]);
        }
        $user_company_manager = User::where(User::TYPE, COMPANY_MANAGER)->first();
        if (!$user_company_manager) {
            $user_company_manager = User::factory()->create([User::TYPE => COMPANY_MANAGER]);
        }
        $user_hr_manager = User::where(User::TYPE, HR_MANAGER)->first();
        if (!$user_hr_manager) {
            $user_hr_manager = User::factory()->create([User::TYPE => HR_MANAGER]);
        }
        $user_hr = User::where(User::TYPE, HR)->first();
        if (!$user_hr) {
            $user_hr = User::factory()->create([User::TYPE => HR]);
        }

        $this->user_admin = $this->actingAs($user_admin_manager, 'api');
//        $this->user_company_manager = $this->actingAs($user_company_manager, 'api');
//        $this->user_hr_manager = $this->actingAs($user_hr_manager, 'api');
//        $this->user_hr = $this->actingAs($user_hr, 'api');


//        $data  = $this->user_company_manager->get('api/company');

    }

    public function loginWith($type)
    {
        $user_login = User::where(User::TYPE, $type)->first();
        if (!$user_login) {
            $user_login = User::factory()->create([User::TYPE => $type]);
        }

        if ($type == COMPANY) {
            $this->company = Company::firstOrCreate(
                [Company::USER_ID => $user_login->id],
                Company::factory()->make()->toArray()
            );
        }

        if ($type == HR) {
            $hrOrg = HrOrganization::firstOrCreate(
                [HrOrganization::USER_ID => $user_login->id],
                HrOrganization::factory()->make()->toArray())->id;

            $this->hr = HR::firstOrCreate(
                [
                    HR::HR_ORGANIZATION_ID => $hrOrg,
                    HR::USER_ID => $user_login->id,
                    HR::CREATED_BY => $user_login->id
                ],
                HR::factory()->make()->toArray()
            );
        }

        $this->user_login = $this->actingAs($user_login, 'api');
    }
}
