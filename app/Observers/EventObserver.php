<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\User;
use App\Notifications\Event\DateTimeChanged;
use App\Services\Notification\Notification;

class EventObserver
{
    /**
     * Handle the event "updated" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function updated(Event $event)
    {
        if($event->isPublished() && !$event->isPast() && ($event->wasChanged('starts_at') || $event->wasChanged('ends_at'))){
            $user_ids = $event->rsvp()->attending()->pluck('user_id')->toArray();
            $users = User::whereIn('id', $user_ids)->get();
            Notification::send($users, new DateTimeChanged($event));
        }
    }
}
