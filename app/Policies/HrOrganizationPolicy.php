<?php

namespace App\Policies;

use App\Models\HrOrganization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HrOrganizationPolicy
{
    use HandlesAuthorization;

    public function show(User $user, HrOrganization $hrOrganization){
        return $this->checkRole($user, $hrOrganization);
    }

    public function update(User $user, HrOrganization $hrOrganization){
        return $this->checkRole($user, $hrOrganization);
    }

    public function delete(User $user, HrOrganization $hrOrganization){
        return $this->checkRole($user, $hrOrganization);
    }

    private function checkRole(User $user, HrOrganization $hrOrganization){
        return $user->id == $hrOrganization->user_id || $user->type == SUPER_ADMIN || $user->type == HR_MANAGER;
    }
}
