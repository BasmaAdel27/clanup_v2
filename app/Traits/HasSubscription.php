<?php

namespace App\Traits;

use App\Models\Plan;
use App\Models\PlanSubscription;
use App\Services\Period;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasSubscription
{
    /**
     * The user may have many subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(PlanSubscription::class);
    }

    /**
     * The user may have many subscriptions.
     *
     * @return \App\Models\PlanSubscription
     */
    public function subscription()
    {
        return $this->subscriptions()->first();
    }

    /**
     * Return current Subscription Plan
     *
     * @return PlanSubscription|null
     */
    public function currentSubscriptionPlan()
    {        
        $subscription = $this->subscription();

        return $subscription ? $subscription->plan : null;
    }

    /**
     * Subscribe or renew to a plan.
     *
     * @param integer $plan_id
     *
     * @return \App\Models\PlanSubscription
     */
    public function createOrRenewSubscribtion($plan, $data = [])
    {
        // If there are already a subscription then renew
        if ($subscription = $this->subscription()) {
            // Renew if the plan is remain the same
            if ($subscription->plan->id == $plan->id) {
                $subscription->renew();
            } else {
                $subscription->changePlan($plan);
            }
        } else {
            // If there is no subscription create a new one
            $subscription = $this->newSubscription($plan, $data);
        }

        return $subscription;
    }

    /**
     * Subscribe user to a new plan.
     *
     * @param \App\Models\Plan $plan
     *
     * @return \App\Models\PlanSubscription
     */
    public function newSubscription(Plan $plan, $data = []): PlanSubscription
    {
        $trial = new Period($plan->trial_interval, $plan->trial_period, now());
        $period = new Period($plan->invoice_interval, $plan->invoice_period, $trial->getEndDate());

        return $this->subscriptions()->create([
            'plan_id' => $plan->getKey(),
            'trial_ends_at' => $trial->getEndDate(),
            'starts_at' => $period->getStartDate(),
            'ends_at' => $period->getEndDate(),
            'data' => $data,
        ]);
    }
}