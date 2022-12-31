<?php

namespace App\Http\Controllers\Application\Components\Group\Event\Actions;

use App\Models\EventRSVP;
use Livewire\Component;

class AttendEvent extends Component
{
    public $event;
    public $show_modal = false;
    public $rsvp_guests;
    public $rsvp_question_answer;

    public function mount()
    {
        $rsvp = $this->get_rsvp();
        $this->update_rsvp_inputs($rsvp);
    }

    public function close_modal() {
        $this->show_modal = false;
    }

    public function show_modal() {
        $this->show_modal = true;
    }

    private function get_rsvp()
    {
        if (!$user = auth()->user()) return;

        if ($rsvp = $this->event->userAttending($user)->first()) {
            $this->rsvp_guests = $rsvp->guests;
            $this->rsvp_question_answer = $rsvp->question_answer;
        }
    }

    public function update_rsvp_inputs($rsvp)
    {
        if ($rsvp) {
            $this->rsvp_guests = $rsvp->guests;
            $this->rsvp_question_answer = $rsvp->question_answer;
        }
    }

    public function attend()
    {
        if (!$user = auth()->user()) return;

        // Check if the user is member of the group
        if (!$user->isMemberOf($this->event->group)) {
            $joined = $user->joinToGroup($this->event->group);
            if (!$joined) {
                // Return null if the user cannot join this group ie. blacklisted etc.
                session()->flash('alert-danger', __('You can not join this group. Please contact with the organizer to join this group.'));
                return null;
            }
        }

        // Save the RSVP on the database
        $rsvp = EventRSVP::updateOrCreate(
            ['event_id' => $this->event->id, 'user_id' => $user->id], 
            ['response' => $user->isMemberOf($this->event->group) ? EventRSVP::COMING : EventRSVP::WAITING_APPROVAL, 'pay_status' => EventRSVP::NONE]
        );

        if ($rsvp && ($rsvp->response == EventRSVP::COMING || $rsvp->response == EventRSVP::WAITING_APPROVAL)) {
            $this->show_modal();
        }
    }

    public function update_rsvp($response)
    {
        if (!$user = auth()->user()) return;

        $rsvp = $this->event->userAttending($user)->first();

        if ($this->event->allowed_guests) {
            $this->validate([
                'rsvp_guests' => 'integer|min:0|max:' . $this->event->allowed_guests
            ]);
        }

        $rsvp->update([
            'guests' => $this->rsvp_guests ?? 0,
            'question_answer' => $this->rsvp_question_answer ?? '',
            'response' => $response == 'going' ? EventRSVP::COMING : EventRSVP::NOT_COMING,
        ]);

        $this->update_rsvp_inputs($rsvp);
        $this->close_modal();
    }
    
    public function render()
    {
        return view('application.components.group.event.actions.attend-event');
    }
}
