<?php

namespace App\Policies;

use App\{Report, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any reports.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(?User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the report.
     *
     * @param  \App\User  $user
     * @param  \App\Report  $report
     * @return mixed
     */
    public function view(?User $user, Report $report)
    {
        return true;
    }

    /**
     * Determine whether the user can create reports.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return !empty($user->id);
    }

    /**
     * Determine whether the user can update the report.
     *
     * @param  \App\User  $user
     * @param  \App\Report  $report
     * @return mixed
     */
    public function update(?User $user, Report $report)
    {
        return $report->user_id == optional($user)->id || !optional($user)->isUser();
    }

    /**
     * Determine whether the user can delete the report.
     *
     * @param  \App\User  $user
     * @param  \App\Report  $report
     * @return mixed
     */
    public function delete(User $user, Report $report)
    {
        return $report->user_id == $user->id || !$user->isUser();
    }

    /**
     * Determine whether the user can restore the report.
     *
     * @param  \App\User  $user
     * @param  \App\Report  $report
     * @return mixed
    public function restore(User $user, Report $report)
    {
    }
     */

    /**
     * Determine whether the user can permanently delete the report.
     *
     * @param  \App\User  $user
     * @param  \App\Report  $report
     * @return mixed
    public function forceDelete(User $user, Report $report)
    {
    }
     */
}
