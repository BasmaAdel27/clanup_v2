<?php

namespace App\Console\Commands;

use App\Models\PlanSubscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ExpiredSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel:expired_subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel expired subscriptions';

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
        $subscriptions = PlanSubscription::findExpired()->get();

        // Loop subscriptions
        foreach ($subscriptions as $subscription) {
            // Cancel subscription
            $subscription->cancel(true);
        }
    }
}