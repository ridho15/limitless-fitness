<?php

namespace App\Policies;

use App\Models\KelasRating;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KelasRatingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        if ($user->hasRole(['Instruktur'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, KelasRating $kelasRating)
    {
        if ($user->hasRole(['Instruktur'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, KelasRating $kelasRating)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KelasRating $kelasRating)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, KelasRating $kelasRating)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, KelasRating $kelasRating)
    {
        //
    }
}
