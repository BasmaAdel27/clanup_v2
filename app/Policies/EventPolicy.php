<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
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
     * @param  \App\Models\Event $event
     * @return mixed
     */
    public function view(?User $user, Event $event)
    {
        if (
            (!$user && $event->group->isClosed()) ||
            ($user && $event->group->isClosed() && !$user->isMemberOf($event->group))
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
        return $user->hasOrganizerRolesOf($group);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return mixed
     */
    public function update(User $user, Event $event)
    {
        return $user->hasOrganizerRolesOf($event->group);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return mixed
     */
    public function delete(User $user, Event $event)
    {
        return $user->hasOrganizerRolesOf($event->group);
    }
}
