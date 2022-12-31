<?php

namespace App\Http\Controllers\Application\Components\Group\Event;

use App\Models\EventRSVP;
use Livewire\Component;

class Attendees extends Component
{
    public $event;
    public $attending_count;
    public $not_attending_count;
    public $tab = 'going';
    public $count;
    public $limit = 50;
    public $search;

    protected $queryString = [
        'tab' => ['except' => 'going'], 
    ];

    public function mount()
    {
        $this->count();
    }

    private function count()
    {
        $this->attending_count = $this->event->rsvp()->attending()->count();
        $this->not_attending_count = $this->event->rsvp()->notAttending()->count();
    }

    public function loadMore() {
        $this->limit += 50;
    }
    
    public function change_response($rsvp_id, $response) {
        $user = auth()->user();

        if ($user && $user->hasOrganizerRolesOf($this->event->group)) {
            if ($rsvp = EventRSVP::find($rsvp_id)) {
                if ($response == 'going') {
                    $rsvp->markAsComing();
                } else if ($response == 'not_going') {
                    $rsvp->markAsNotComing();
                }
                $this->count();
            }
        }
    }

    public function render()
    {
        if ($this->tab == 'going') {
            $query = $this->event->rsvp()->attending()->searchUser($this->search)->take($this->limit)->get();
            $this->count = $query->count();      
        } else if ($this->tab == 'not_going') {
            $query = $this->event->rsvp()->notAttending()->searchUser($this->search)->take($this->limit)->get();
            $this->count = $query->count();  
        } else {
            $query = [];
        }

        return view('application.components.group.event.attendees', ['attendees' => $query]);
    }
}
