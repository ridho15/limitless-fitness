<?php

namespace App\Policies;

use App\Models\KelasActivity;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KelasActivityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        if ($user->hasRole(['Instruktur', 'Admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, KelasActivity $kelasActivity)
    {
        if ($user->hasRole(['Instruktur', 'Admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        if ($user->hasRole(['Instruktur', 'Admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, KelasActivity $kelasActivity)
    {
        if ($user->hasRole(['Instruktur', 'Admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KelasActivity $kelasActivity)
    {
        if ($user->hasRole(['Instruktur', 'Admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, KelasActivity $kelasActivity)
    {

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, KelasActivity $kelasActivity)
    {
        //
    }
}
