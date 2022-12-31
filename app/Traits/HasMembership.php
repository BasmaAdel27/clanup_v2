<?php

namespace App\Traits;

use App\Models\Group;
use App\Models\GroupMembership;
use App\Models\Visit;

trait HasMembership
{
    /**
     * Returns all memberships
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memberships()
    {
        return $this->hasMany(GroupMembership::class);
    }

    /**
     * Returns all the groups this user owned.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function owning_groups()
    {
        return $this->hasMany(Group::class, 'created_by')->withTrashed();
    }

    /**
     * Returns all the groups this user is part of.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups($role = GroupMembership::MEMBER, $privacy = Group::OPEN, $member_role_operator = '>=', $privacy_role_operator = '>=')
    {
        return $this->belongsToMany(Group::class, 'group_memberships')
            ->where('group_memberships.membership', $member_role_operator, $role)
            ->where('groups.group_type', $privacy_role_operator, $privacy)
            ->withTimestamps();
    }

    /**
     * Returns true if the user is organizer of $group
     */
    public function isOrganizerOf(Group $group)
    {
        return GroupMembership::forUser($this)->forGroup($group)->forMembership('=', GroupMembership::ORGANIZER)->exists();
    }

    /**
     * Returns true if the user is organizer of $group
     */
    public function isCoOrganizerOf(Group $group)
    {
        return GroupMembership::forUser($this)->forGroup($group)->forMembership('=', GroupMembership::CO_ORGANIZER)->exists();
    }

    /**
     * Returns true if the user is organizer of $group
     */
    public function isAssistantOrganizerOf(Group $group)
    {
        return GroupMembership::forUser($this)->forGroup($group)->forMembership('=', GroupMembership::ASSISTANT_ORGANIZER)->exists();
    }

    /**
     * Returns true if the user is organizer of $group
     */
    public function isEventOrganizerOf(Group $group)
    {
        return GroupMembership::forUser($this)->forGroup($group)->forMembership('=', GroupMembership::EVENT_ORGANIZER)->exists();
    }

    /**
     * Returns true if the user is member of $group.
     */
    public function isMemberOf(Group $group)
    {
        return GroupMembership::forUser($this)->forGroup($group)->forMembership('>=', GroupMembership::MEMBER)->exists();
    }

    /**
     * Returns true if the user is candidate of $group
     */
    public function isCandidateOf(Group $group)
    {
        return GroupMembership::forUser($this)->forGroup($group)->forMembership('=', GroupMembership::CANDIDATE)->exists();
    }

    /**
     * Returns true if the user is removed from $group
     */
    public function isRemovedFrom(Group $group)
    {
        return GroupMembership::forUser($this)->forGroup($group)->forMembership('=', GroupMembership::REMOVED)->exists();
    }

    /**
     * Returns true if the user is banned from $group
     */
    public function isBannedFrom(Group $group)
    {
        return GroupMembership::forUser($this)->forGroup($group)->forMembership('=', GroupMembership::BLACKLISTED)->exists();
    }

    /**
     * Returns true if the user has any of the organizer roles of $group
     */
    public function hasOrganizerRolesOf(Group $group)
    {
        return GroupMembership::forUser($this)->forGroup($group)->forMembership('>=', GroupMembership::EVENT_ORGANIZER)->exists();
    }

    /**
     * Returns true if the user has above of given role of $group
     */
    public function hasAboveAssistantRolesOf(Group $group)
    {
        return GroupMembership::forUser($this)->forGroup($group)->forMembership('>=', GroupMembership::ASSISTANT_ORGANIZER)->exists();
    }

    /**
     * Returns true if the user has above of given role of $group
     */
    public function hasAnyGivenRoles(Group $group, array $roles)
    {
        return GroupMembership::forUser($this)->forGroup($group)->whereIn('membership', $roles)->exists();
    }

    /**
     * Returns true if the user has any of the organizer roles of $group
     */
    public function isOrganizerOfAnyGroup()
    {
        return $this->groups(GroupMembership::EVENT_ORGANIZER)->exists();
    }

    /**
     * Returns the role of the user for given $group
     * 
     * @return string
     */
    public function getRoleOf(Group $group)
    {
        if ($this->isOrganizerOf($group))
            return __('Organizer');
        elseif ($this->isCoOrganizerOf($group))
            return __('Co-organizer');
        elseif ($this->isAssistantOrganizerOf($group))
            return __('Assistant organizer');
        elseif ($this->isEventOrganizerOf($group))
            return __('Event organizer');
        elseif ($this->isMemberOf($group))
            return __('Member');
        else
            return '';
    }

    /**
     * Returns the last visit date of the user for given $group
     * 
     * @return string|null
     */
    public function getLastVisitDateOf(Group $group) 
    {
        return optional(
            Visit::where('user_id', $this->id)->where('group_id', $group->id)->orderBy('created_at', 'desc')->first()
        )->date;
    }

    /**
     * Join user to given group
     * 
     * @return GroupMembership
     */
    public function joinToGroup(Group $group)
    {
        if($this->hasAnyGivenRoles($group, [
            GroupMembership::MEMBER, 
            GroupMembership::BLACKLISTED, 
            GroupMembership::CANDIDATE
        ])) return;

        return GroupMembership::updateOrCreate(
            ['user_id' => $this->id, 'group_id' => $group->id],
            ['membership' => (
                $group->isOpen() 
                && !$group->getSetting('new_members_need_approved') 
            ) ? GroupMembership::MEMBER : GroupMembership::CANDIDATE]
        );
    }

    /**
     * Unsubscribe user to given group
     * 
     * @return GroupMembership
     */
    public function unsubscribeFromGroup(Group $group)
    {
        if($this->isOrganizerOf($group) || !$this->isMemberOf($group)) return;

        return GroupMembership::updateOrCreate(
            ['user_id' => $this->id, 'group_id' => $group->id],
            ['membership' => GroupMembership::UNSUBSCRIBED]
        );
    }

    /**
     * Revert user join request to given group
     * 
     * @return GroupMembership
     */
    public function revertJoinRequest(Group $group)
    {
        if(!$this->isCandidateOf($group)) return;
        return GroupMembership::where('group_id', $group->id)->where('user_id', $this->id)->delete();
    }
}