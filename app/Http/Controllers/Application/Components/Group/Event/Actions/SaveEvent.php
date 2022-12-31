<?php

namespace App\Http\Controllers\Application\Components\Group\Event\Actions;

use Livewire\Component;

class SaveEvent extends Component
{
    public $event;
    public $button = false;
    public $icon_class = '';

    public function save()
    {
        // Redirect user to login if not authenticated
        if (!$user = auth()->user()) {
            return redirect()->route('login');
        } 

        // Toggle saved status
        if ($user->isSaved($this->event)) {
            $user->removeSave($this->event);
        } else {
            $ip = request()->ip();
            $user_agent = request()->userAgent();
            
            $this->event->saves()->create([
                'user_id' => $user->id,
                'ip' => $ip,
                'user_agent' => $user_agent,
            ]);
        }
    }

    public function render()
    {
        return view('application.components.group.event.actions.save-event');
    }
}
