<?php

namespace App\Observers;

use App\Events\Group\MembershipChanged as GroupMembershipChanged;
use App\Events\Group\MembershipCreated;
use App\Jobs\ProcessWaitingApprovalEventRSVP;
use App\Models\GroupMembership;
use App\Notifications\Group\MembershipChanged;
use App\Notifications\Group\Organizer\CandidateRequested;
use App\Notifications\Group\Organizer\MemberJoined;
use App\Notifications\Group\Organizer\MemberLeaved;
use App\Services\Notification\Notification;

class GroupMembershipObserver
{
    /**
     * Handle the group "created" event.
     *
     * @param  \App\Models\GroupMembership  $membership
     * @return void
     */
    public function created(GroupMembership $membership)
    {
        MembershipCreated::dispatch($membership);

        // Send notification when new candidate requested to join the group
        if($membership->membership == GroupMembership::CANDIDATE){
            $users = $membership->group->all_organizers;
            Notification::send($users, new CandidateRequested($membership));
        }

        // Send notification when new member joined to the group
        if($membership->membership == GroupMembership::MEMBER){
            $users = $membership->group->all_organizers;
            Notification::send($users, new MemberJoined($membership));
        }
    }

    /**
     * Handle the group sponsor "updated" event.
     *
     * @param  \App\Models\GroupMembership  $membership
     * @return void
     */
    public function updated(GroupMembership $membership)
    {
        GroupMembershipChanged::dispatch($membership);

        // Approve all event RSVP which waiting approval
        if($membership->wasChanged('membership') && $membership->getOriginal('membership') == GroupMembership::CANDIDATE) {
            if ($membership->membership >= GroupMembership::MEMBER) {
                ProcessWaitingApprovalEventRSVP::dispatch($membership, 'markAsComing');
            } else {
                ProcessWaitingApprovalEventRSVP::dispatch($membership, 'delete');
            }
        }
        
        // Send member leaved to all group organizers
        if($membership->wasChanged('membership') && $membership->membership == GroupMembership::UNSUBSCRIBED){
            $users = $membership->group->all_organizers;
            Notification::send($users, new MemberLeaved($membership));
        }

        // Notify members if their role has been changed
        if($membership->wasChanged('membership') && $membership->membership != GroupMembership::UNSUBSCRIBED){
            $membership->user->notify(new MembershipChanged($membership));
        }
    }
}
