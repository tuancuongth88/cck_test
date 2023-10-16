<?php

namespace App\Policies;

use App\Models\User;
use App\Models\HR;
use Illuminate\Auth\Access\HandlesAuthorization;

class HrPolicy
{
    use HandlesAuthorization;

    public function update(User $user, HR $hr)
    {
        return $user->id == $hr->user_id || $user->type == SUPER_ADMIN || $user->type == HR_MANAGER;
    }

    public function updateFileHR(User $user, HR $hr): bool
    {
        return $user->id == $hr->user_id || $user->type == SUPER_ADMIN || $user->type == HR_MANAGER;
    }

    public function delete(User $user, HR $hr)
    {
        if (($user->id == $hr->user_id) || $user->type == SUPER_ADMIN || $user->type == HR_MANAGER){
            return true;
        }
        return false;
    }

    public function show(User $user, HR $hr)
    {
        if ($user->type == \HR){
            if ($user->id == $hr->user_id){
                return true;
            }
            return false;
        }
        return true;
    }

}
