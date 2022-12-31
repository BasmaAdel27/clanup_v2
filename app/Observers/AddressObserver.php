<?php

namespace App\Observers;

use App\Models\Address;
use App\Models\Event;
use App\Models\User;
use App\Notifications\Event\AddressChanged;
use App\Services\Notification\Notification;
use Illuminate\Support\Facades\Log;

class AddressObserver
{
    /**
     * Handle the event "updated" event.
     *
     * @param  \App\Models\Address  $event
     * @return void
     */
    public function updated(Address $address)
    {
        if($address->model_type == 'App\Models\Event' && $address->wasChanged('address_1')){
            $event = Event::find($address->model_id);
            $user_ids = $event->rsvp()->attending()->pluck('user_id')->toArray();
            $users = User::whereIn('id', $user_ids)->get();
            Notification::send($users, new AddressChanged($event));
        }
    }
}
