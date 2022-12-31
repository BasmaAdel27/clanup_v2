<?php

namespace App\Observers;

use App\Models\Discussion;

class DiscussionObserver
{
    /**
     * Holds the methods names of Eloquent Relations 
     * to fall on delete cascade or on restoring
     * 
     * @var array
     */
    protected static $relations_to_cascade = ['comments']; 

    /**
     * Handle the discussion "deleting" event.
     *
     * @param  \App\Discussion  $discussion
     * @return void
     */
    public function deleting(Discussion $discussion)
    {
        foreach (static::$relations_to_cascade as $relation) {
            $relation = $discussion->isForceDeleting() ? $discussion->{$relation}()->withTrashed() : $discussion->{$relation}();
            foreach ($relation->get() as $item) {
                $discussion->isForceDeleting() ? $item->forceDelete() : $item->delete();
            }
        }
    }

    /**
     * Handle the discussion "restoring" event.
     *
     * @param  \App\Discussion  $discussion
     * @return void
     */
    public function restoring(Discussion $discussion)
    {
        foreach (static::$relations_to_cascade as $relation) {
            foreach ($discussion->{$relation}()->withTrashed()->get() as $item) {
                $item->withTrashed()->restore();
            }
        }
    }
}
