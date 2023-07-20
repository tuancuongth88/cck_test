<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserFavorite;
use Illuminate\Auth\Access\HandlesAuthorization;

class FavoritePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model
     * @param User $user
     * @param UserFavorite $favorite
     * @return bool
     */
    public function delete(User $user, UserFavorite $favorite) {
        if ($user->id == $favorite->user_id) {
            return true;
        }
        return false;
    }
}
