<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Company $company){
        return $this->checkRole($user, $company);
    }

    public function update(User $user, Company $company){
        return $this->checkRole($user, $company);
    }

    public function delete(User $user, Company $company){
        return $this->checkRole($user, $company);
    }

    private function checkRole(User $user, Company $company){
        return $user->id == $company->user_id || $user->type == SUPER_ADMIN || $user->type == COMPANY_MANAGER;
    }
}
