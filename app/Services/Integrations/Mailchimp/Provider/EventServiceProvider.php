<?php

namespace App\Services\Integrations\Mailchimp\Provider;

use App\Events\Group\MembershipChanged;
use App\Events\Group\MembershipCreated;
use App\Services\Integrations\Mailchimp\Listeners\MembershipChangedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MembershipCreated::class => [
            MembershipChangedListener::class,
        ],
        MembershipChanged::class => [
            MembershipChangedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}