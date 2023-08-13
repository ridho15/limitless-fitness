<?php

namespace App\Policies;

use App\Models\BookingKelas;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookingKelasPolicy
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
    public function view(User $user, BookingKelas $bookingKelas)
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
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BookingKelas $bookingKelas)
    {
        if($user->hasRole(['Admin'])){
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BookingKelas $bookingKelas)
    {
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BookingKelas $bookingKelas)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BookingKelas $bookingKelas)
    {
        //
    }
}
