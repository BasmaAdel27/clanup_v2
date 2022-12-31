<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\PlanSubscription;
use App\Models\SystemSetting;
use App\Services\Gateways\Dummy;
use App\Services\Gateways\Stripe;
use Illuminate\Console\Command;

class RenewSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'renew:subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renew expired subscriptions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get expired subscriptions
        $subscriptions = PlanSubscription::findOnGrace()->get();

        // Loop subscriptions
        foreach ($subscriptions as $subscription) {
            // Charge stripe
            if (SystemSetting::isStripeActive() && !$subscription->plan->isFree()) {
                $stripe = new Stripe($subscription->user, $subscription->plan);
                $stripe->chargeExistingCard(null);
            }

            // Charge dummy
            if (SystemSetting::isDummyPaymentActive() || $subscription->plan->isFree()) {
                $dummy = new Dummy($subscription->user, $subscription->plan);
                $dummy->charge();
            }
        }
    }
}