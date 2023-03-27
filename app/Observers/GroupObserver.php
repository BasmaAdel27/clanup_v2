<?php

namespace App\Observers;

use App\Models\Group;
use App\Notifications\Group\ContentVisibilityChanged;
use App\Services\Notification\Notification;

class GroupObserver
{
    /**
     * Holds the methods names of Eloquent Relations 
     * to fall on delete cascade or on restoring
     * 
     * @var array
     */
    protected static $relations_to_cascade = ['events', 'discussions', 'sponsors']; 

    /**
     * Handle the group "creating" event.
     *
     * @param  \App\Group  $group
     * @return void
     */
    public function creating(Group $group)
    {
        // Create new uid
        $uid = uniqid();
        while (Group::where('uid', '=', $uid)->count() > 0) {
            $uid = uniqid();
        }
        $group->uid = $uid;
    }

    /**
     * Handle the group "created" event.
     *
     * @param  \App\Group  $group
     * @return void
     */
    public function created(Group $group)
    {
        $user = $group->createdBy;
        if ($user && $user->subscription()) {
            $user->subscription()->recordFeatureUsage('groups');
        }
    }

    /**
     * Handle the group "updated" event.
     *
     * @param  \App\Models\Group  $group
     * @return void
     */
    public function updated(Group $group)
    {
        if($group->wasChanged('group_type') && $group->isClosed()){
            try {
                $users = $group->members;
                Notification::send($users, new ContentVisibilityChanged($group));
            } catch (\Throwable $th) {}

        }
    }

    /**
     * Handle the group "deleting" event.
     *
     * @param  \App\Group  $group
     * @return void
     */
    public function deleting(Group $group)
    {
        foreach (static::$relations_to_cascade as $relation) {
            $relation = $group->isForceDeleting() ? $group->{$relation}()->withTrashed() : $group->{$relation}();
            foreach ($relation->get() as $item) {
                $group->isForceDeleting() ? $item->forceDelete() : $item->delete();
            }
        }
    }

    /**
     * Handle the group "deleted" event.
     *
     * @param  \App\Group  $group
     * @return void
     */
    public function deleted(Group $group)
    {
        $user = $group->createdBy;
        if ($user && $user->subscription()) {
            $user->subscription()->reduceFeatureUsage('groups');
        }
    }

    /**
     * Handle the group "restoring" event.
     *
     * @param  \App\Group  $group
     * @return void
     */
    public function restoring(Group $group)
    {
        foreach (static::$relations_to_cascade as $relation) {
            foreach ($group->{$relation}()->withTrashed()->get() as $item) {
                $item->withTrashed()->restore();
            }
        }
    }

    /**
     * Handle the group "restored" event.
     *
     * @param  \App\Group  $group
     * @return void
     */
    public function restored(Group $group)
    {
        $user = $group->createdBy;
        if ($user && $user->subscription()) {
            $user->subscription()->recordFeatureUsage('groups');
        }
    }
}
