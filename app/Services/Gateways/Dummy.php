<?php

namespace App\Services\Gateways;

use App\Models\Order;

class Dummy
{
    public $user;
    public $plan;

    /**
     * Dummy Construct
     */
    function __construct($user, $plan = null)
    {
        $this->user = $user;
        $this->plan = $plan;
    }

    /**
     * @return mixed
     */
    public function charge()
    {
        try {
            // Create or renew subscribtion
            $this->user->createOrRenewSubscribtion($this->plan);

            // Create and store the order in database
            Order::create([
                'user_id' => $this->user->id,
                'plan_id' => $this->plan->id,
                'amount' => $this->plan->price,
                'currency' => $this->plan->currency,
                'transaction_id' => '-',
                'payment_type' => '-',
                'payment_status' => 1,
            ]);
        } catch (\Exception $e) {}
    }
}
