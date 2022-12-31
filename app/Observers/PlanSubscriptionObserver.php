<?php

namespace App\Observers;

use App\Models\PlanSubscription;

class PlanSubscriptionObserver
{
    /**
     * Handle the plan subscription "deleting" event.
     *
     * @param  \App\PlanSubscription  $planSubscription
     * @return void
     */
    public function deleting(PlanSubscription $planSubscription)
    {
        $planSubscription->usage()->delete();
    }
}
