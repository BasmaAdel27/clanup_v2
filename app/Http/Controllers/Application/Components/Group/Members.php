<?php

namespace App\Http\Controllers\Application\Components\Group;

use App\Models\GroupMembership;
use Livewire\Component;

class Members extends Component
{
    public $group;
    public $group_candidates_count;
    public $group_member_count;
    public $group_organizers_count;
    public $tab = 'all';
    public $member_limit = 15;
    public $search;

    protected $queryString = [
        'tab' => ['except' => 'all'],
    ];

    public function mount()
    {
        $this->count();
    }

    private function count()
    {
        $this->group_candidates_count = $this->group->candidates()->count();
        $this->group_member_count = $this->group->members()->count();
        $this->group_organizers_count = $this->group->all_organizers()->count();
    }

    public function loadMore() {
        $this->member_limit += 15;
    }

    public function changeRoleOfMember($memberId, $role)
    {
        $user = auth()->user();

        if (!$user || !in_array($role, [
            GroupMembership::CO_ORGANIZER,
            GroupMembership::ASSISTANT_ORGANIZER,
            GroupMembership::EVENT_ORGANIZER,
            GroupMembership::MEMBER,
            GroupMembership::REMOVED,
            GroupMembership::BLACKLISTED,
        ])) return;

        // Find membership
        $membership = GroupMembership::whereUserId($memberId)->forGroup($this->group)->firstOrFail();

        // Change member roles by authorization
        if ($user->isOrganizerOf($this->group)) {
            $membership->membership = $role;
        } else if ($user->isCoOrganizerOf($this->group) && $role <= GroupMembership::ASSISTANT_ORGANIZER) {
            $membership->membership = $role;
        } else if ($user->hasAboveAssistantRolesOf($this->group) && $role <= GroupMembership::MEMBER) {
            $membership->membership = $role;
        }

        $membership->save();
        $this->count();
    }

    public function render()
    {
        if ($this->tab == 'organizers') {
            $query = $this->group->all_organizers()->search($this->search);
        } else if ($this->tab == 'candidates' && auth()->user() && auth()->user()->hasAboveAssistantRolesOf($this->group)) {
            $query = $this->group->candidates()->search($this->search);
        } else {
            $query = $this->group->members()->search($this->search)->take($this->member_limit);
        }

        return view('application.components.group.members', ['members' => $query->get()]);
    }
}
