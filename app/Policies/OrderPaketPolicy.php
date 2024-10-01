<?php

namespace App\Policies;

use App\Models\OrderPaket;
use App\Models\User;
use GuzzleHttp\RetryMiddleware;
use Illuminate\Auth\Access\Response;

class OrderPaketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        if ($user->hasRole(['Admin', 'Finance'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OrderPaket $orderPaket)
    {
        if ($user->hasRole(['Admin', 'Finance'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OrderPaket $orderPaket)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OrderPaket $orderPaket)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OrderPaket $orderPaket)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OrderPaket $orderPaket)
    {
        //
    }
}
