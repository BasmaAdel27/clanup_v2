<?php

namespace App\Policies;

use App\Models\DiscussionComment;
use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscussionCommentPolicy
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
     * @param  \App\Models\DiscussionComment  $comment
     * @return mixed
     */
    public function view(?User $user, DiscussionComment $comment)
    {
        if (
            (!$user && $comment->discussion->group->isClosed()) ||
            ($user && $comment->discussion->group->isClosed() && !$user->isMemberOf($comment->discussion->group))
        )  return false;
        
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User   $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function create(User $user, Group $group)
    {
        return $user->isMemberOf($group);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DiscussionComment  $comment
     * @return mixed
     */
    public function update(User $user, DiscussionComment $comment)
    {
        return $user->hasOrganizerRolesOf($comment->discussion->group) || $user->id == $comment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DiscussionComment  $comment
     * @return mixed
     */
    public function delete(User $user, DiscussionComment $comment)
    {
        return $user->hasOrganizerRolesOf($comment->discussion->group) || $user->id == $comment->user_id;
    }
}
