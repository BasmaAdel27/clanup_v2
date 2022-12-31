<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\Discussion;
use App\Models\Event;
use App\Models\Group;
use App\Models\GroupMembership;
use App\Models\GroupSponsor;
use App\Models\Plan;
use App\Models\PlanSubscription;
use App\Models\User;
use App\Observers\DiscussionObserver;
use App\Observers\EventObserver;
use App\Observers\GroupMembershipObserver;
use App\Observers\GroupObserver;
use App\Observers\GroupSponsorObserver;
use App\Observers\PlanObserver;
use App\Observers\PlanSubscriptionObserver;
use App\Observers\UserObserver;
use App\Observers\AddressObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Address::observe(AddressObserver::class);
        Discussion::observe(DiscussionObserver::class);
        Event::observe(EventObserver::class);
        Group::observe(GroupObserver::class);
        GroupSponsor::observe(GroupSponsorObserver::class);
        GroupMembership::observe(GroupMembershipObserver::class);
        Plan::observe(PlanObserver::class);
        PlanSubscription::observe(PlanSubscriptionObserver::class);
        User::observe(UserObserver::class);
    }
}
