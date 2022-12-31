<?php

namespace App\Policies;

use App\Models\Discussion;
use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscussionPolicy
{
    use HandlesAuthorization;

    /**
     * Filter policies
     */
    public function before($user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function viewAny(?User $user, Group $group)
    {
        if (
            (!$user && $group->isClosed()) ||
            ($user && $group->isClosed() && !$user->isMemberOf($group))
        )  return false;
        
        return true;
    }

    /**
     * Determine whether the user can view model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Discussion  $discussion
     * @return mixed
     */
    public function view(?User $user, Discussion $discussion)
    {
        if (
            (!$user && $discussion->group->isClosed()) ||
            ($user && $discussion->group->isClosed() && !$user->isMemberOf($discussion->group))
        )  return false;
        
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function create(User $user, Group $group)
    {
        return $user->hasOrganizerRolesOf($group) || ($user->isMemberOf($group) && $group->getSetting('allow_members_create_discussion'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Discussion  $discussion
     * @return mixed
     */
    public function update(User $user, Discussion $discussion)
    {
        return $user->hasOrganizerRolesOf($discussion->group) || $user->id == $discussion->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Discussion  $discussion
     * @return mixed
     */
    public function delete(User $user, Discussion $discussion)
    {
        return $user->hasOrganizerRolesOf($discussion->group) || $user->id == $discussion->user_id;
    }
}
