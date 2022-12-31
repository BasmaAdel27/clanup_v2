<?php

namespace App\Observers;

use App\Models\Plan;

class PlanObserver
{
    /**
     * Holds the methods names of Eloquent Relations 
     * to fall on delete cascade or on restoring
     * 
     * @var array
     */
    protected static $relations_to_cascade = ['features', 'subscriptions']; 

    /**
     * Handle the plan "deleting" event.
     *
     * @param  \App\Plan  $plan
     * @return void
     */
    public function deleting(Plan $plan)
    {
        foreach (static::$relations_to_cascade as $relation) {
            foreach ($plan->{$relation}()->get() as $item) {
                $item->delete();
            }
        }
    }

    /**
     * Handle the plan "restoring" event.
     *
     * @param  \App\Plan  $plan
     * @return void
     */
    public function restoring(Plan $plan)
    {
        foreach (static::$relations_to_cascade as $relation) {
            foreach ($plan->{$relation}()->withTrashed()->get() as $item) {
                $item->withTrashed()->restore();
            }
        }
    }
}
