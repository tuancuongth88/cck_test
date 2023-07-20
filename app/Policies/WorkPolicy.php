<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Work;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Work  $user
     * @param  \App\Models\User  $work
     * @return mixed
     */
    public function update(User $user, Work $work)
    {
        if (($user->id == $work->user_id) || $user->type == SUPER_ADMIN || $user->type == COMPANY_MANAGER){
            return true;
        }
        return false;
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Work  $work
     * @return mixed
     */
    public function delete(User $user, Work $work)
    {
        if (($user->id == $work->user_id) || $user->type == SUPER_ADMIN || $user->type == COMPANY_MANAGER){
            return true;
        }
        return false;
    }
}
