<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
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
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function view_members(?User $user, Group $group)
    {
        if (
            (!$user && $group->isClosed()) ||
            ($user && $group->isClosed() && !$user->isMemberOf($group))
        )  return false;
        
        return true;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function view_photos(?User $user, Group $group)
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
     * @param  \App\Models\Group $group
     * @return mixed
     */
    public function view(?User $user, Group $group)
    {
        if (
            (!$user && $group->isClosed()) ||
            ($user && $group->isClosed() && !$user->isMemberOf($group))
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
    public function create(User $user)
    {
        if ($subscription = $user->subscription()) {
            if (($subscription->isActive() || $subscription->onGrace()) && $subscription->canUseFeature('groups')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function update(User $user, Group $group)
    {
        $organizer = $group->createdBy;

        if ($organizer) {
            $subscription = $organizer->subscription();
            if ($subscription && ($subscription->isActive() || $subscription->onGrace()) && ($user->isOrganizerOf($group) || $user->isCoOrganizerOf($group))) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function delete(User $user, Group $group)
    {
        return $user->isOrganizerOf($group);
    }

    /**
     * Determine whether the user can store a group photo.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function store_photo(User $user, Group $group)
    {
        return $user->hasOrganizerRolesOf($group);
    }

    /**
     * Determine whether the user can delete a group photo.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function delete_photo(User $user, Group $group)
    {
        return $user->hasOrganizerRolesOf($group);
    }

    /**
     * Determine whether the user can list a group sponsor.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function list_sponsor(?User $user, Group $group)
    {
        $organizer = $group->createdBy;

        if ($organizer) {
            $subscription = $organizer->subscription();
            if (
                $subscription && 
                $subscription->canUseFeature('can_display_sponsors') &&
                ($subscription->isActive() || $subscription->onGrace())
            ) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Determine whether the user can store a group sponsor.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function store_sponsor(User $user, Group $group)
    {
        $organizer = $group->createdBy;

        if ($organizer) {
            $subscription = $organizer->subscription();
            if (
                $subscription && 
                ($subscription->isActive() || $subscription->onGrace()) && 
                $subscription->canUseFeature('max_sponsors_count') && 
                ($user->isOrganizerOf($group) || $user->isCoOrganizerOf($group))
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update a group sponsor.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function update_sponsor(User $user, Group $group)
    {
        return $user->isOrganizerOf($group) || $user->isCoOrganizerOf($group);
    }

    /**
     * Determine whether the user can delete a group sponsor.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function delete_sponsor(User $user, Group $group)
    {
        return $user->isOrganizerOf($group) || $user->isCoOrganizerOf($group);
    }

    /**
     * Determine whether the user can see attendee email.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function see_attendee_email(User $user, Group $group)
    {
        $organizer = $group->createdBy;

        if ($organizer) {
            $subscription = $organizer->subscription();
            if (
                $subscription && 
                ($subscription->isActive() || $subscription->onGrace()) && 
                $subscription->canUseFeature('can_access_email_addresses') && 
                $user->hasOrganizerRolesOf($group)
            ) {
                return true;
            }
        }

        return false;
    }
}
