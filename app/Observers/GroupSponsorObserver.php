<?php

namespace App\Observers;

use App\Models\GroupSponsor;

class GroupSponsorObserver
{
    /**
     * Handle the group sponsor "created" event.
     *
     * @param  \App\GroupSponsor  $groupSponsor
     * @return void
     */
    public function created(GroupSponsor $groupSponsor)
    {
        $group = $groupSponsor->group;
        $user = $group->createdBy;
        if ($user && $user->subscription()) {
            $user->subscription()->recordFeatureUsage('max_sponsors_count');
        }
    }

    /**
     * Handle the group sponsor "deleted" event.
     *
     * @param  \App\GroupSponsor  $groupSponsor
     * @return void
     */
    public function deleted(GroupSponsor $groupSponsor)
    {
        $group = $groupSponsor->group;
        $user = $group->createdBy;
        if ($user && $user->subscription()) {
            $user->subscription()->reduceFeatureUsage('max_sponsors_count');
        }
    }

    /**
     * Handle the group sponsor "restored" event.
     *
     * @param  \App\GroupSponsor  $groupSponsor
     * @return void
     */
    public function restored(GroupSponsor $groupSponsor)
    {
        $group = $groupSponsor->group;
        $user = $group->createdBy;
        if ($user && $user->subscription()) {
            $user->subscription()->recordFeatureUsage('max_sponsors_count');
        }
    }
}
