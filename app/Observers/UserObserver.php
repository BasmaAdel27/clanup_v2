<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\User\PasswordChanged;

class UserObserver
{
    /**
     * Holds the methods names of Eloquent Relations 
     * to fall on delete cascade or on restoring
     * 
     * @var array
     */
    protected static $relations_to_cascade = ['subscriptions', 'memberships', 'addresses', 'saves', 'rsvp', 'settings', 'discussions', 'owning_groups']; 

    /**
     * Handle the models. user "creating" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function creating(User $user)
    {
        // Create new uid
        $uid = uniqid();
        while (User::where('uid', '=', $uid)->count() > 0) {
            $uid = uniqid();
        }
        $user->uid = $uid;
        $user->username = 'user'.$uid;
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        // Notify user if their password has been changed
        if($user->wasChanged('password')){
            $user->notify(new PasswordChanged());
        }
    }

    /**
     * Handle the models. user "deleting" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleting(User $user)
    {
        foreach (static::$relations_to_cascade as $relation) {
            $relation = $user->{$relation}();
            if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($relation->getRelated()))) {
                $relation = $relation->withTrashed();
            }
            foreach ($relation->get() as $item) {
                $item->forceDelete();
            }
        }
    }
}
