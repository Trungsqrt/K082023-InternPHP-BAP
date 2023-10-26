<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Seminar_Mail_Info;
use App\Models\User;

class SeminarMailInfoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Seminar_Mail_Info $seminarMailInfo): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Seminar_Mail_Info $seminarMailInfo): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Seminar_Mail_Info $seminarMailInfo): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Seminar_Mail_Info $seminarMailInfo): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Seminar_Mail_Info $seminarMailInfo): bool
    {
        //
    }
}
