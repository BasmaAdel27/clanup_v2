<?php

namespace App\Http\Controllers\Application\Components\Group\Actions;

use Livewire\Component;

class JoinGroup extends Component
{
    public $group;
    public $need_pp = false;
    public $candidate = false;
    public $welcome_member = false;
    public $show_modal = false;

    public function join_group() {
        $user = auth()->user();

        // Redirect user to login
        if (!$user) return redirect()->route('login', ['_redirect' => route('groups.about', ['group' => $this->group->slug])]);

        // Check the user if blacklisted from group
        if ($user->isBannedFrom($this->group)) {
            session()->flash('alert-danger', __('You have banned from this group. You can not join this group.'));
            return null;
        }

        // Attach membership
        if ($this->group->getSetting('new_members_need_pp') && !$user->getMedia()->last()) {
            $this->need_pp = true;
            $this->show_modal = true;
        } else {
            $user->joinToGroup($this->group);
        }

        if ($user->isCandidateOf($this->group)) {
            $this->candidate = true;
            $this->show_modal = true;
        }

        if ($user->isMemberOf($this->group) && $this->group->getSetting('welcome_message')) {
            $this->welcome_member = true;
            $this->show_modal = true;
        }
    }

    public function unsubscribe_from_group() 
    {
        $user = auth()->user();

        // Detach membership
        $user->unsubscribeFromGroup($this->group);    
    }

    public function revert_candidate_request() 
    {
        $user = auth()->user();
        $user->revertJoinRequest($this->group);
        $this->show_modal = false;
        $this->candidate = false;
    }

    public function close_modal() {
        $this->show_modal = false;
    }

    public function render()
    {
        return view('application.components.group.actions.join-group');
    }
}
