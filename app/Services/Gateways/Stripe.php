<?php

namespace App\Services\Gateways;

class Stripe
{
    public $user;
    public $plan;
    protected $gateway;
    protected $customer;

    /**
     * Stripe Construct
     */
    function __construct($user, $plan = null)
    {
        $this->user = $user;
        $this->plan = $plan;
        $this->gateway = $this->getGateway();
        $this->customer = $this->getCustomer();
    }

    /**
     * @return mixed
     */
    public function getGateway()
    {
        return new \Stripe\StripeClient(
             get_system_setting('stripe_secret_key')
        );
    }

    /**
     * @return mixed
     */
    public function getCustomer()
    {
        // Check if there is an already Stripe customer associated with the user
        if ($this->user->stripe_customer_id) {
            $customer = $this->gateway->customers->retrieve($this->user->stripe_customer_id, []);
            if ($customer) return $customer;
        }

        // Create new user if there is not
        $customer = $this->gateway->customers->create([
            'name' => $this->user->full_name,
            'email' => $this->user->email,
            'description' => $this->user->uid,
        ]);

        // Update user with it's customer id
        $this->user->update([
            'stripe_customer_id' => $customer->id,
        ]);

        return $customer;
    }

    /**
     * @return mixed
     */
    public function getAvailablePaymentMethods()
    {
        return $this->gateway->paymentMethods->all(
            ['customer' => $this->customer->id, 'type' => 'card']
        );
    }

    /**
     * @return mixed
     */
    public function getDefaultPaymentMethod()
    {
        $payment_method = $this->gateway->paymentMethods->retrieve($this->user->stripe_pm_id, []);
        if ($payment_method) return $payment_method;

        $available_payment_methods = $this->getAvailablePaymentMethods();
        return isset($available_payment_methods[0]) ? $available_payment_methods[0] : null;
    }

    /**
     * @return mixed
     */
    public function detachPaymentMethod($payment_method_id)
    {
        return $this->gateway->paymentMethods->detach($payment_method_id, []);
    }

    /**
     * @return mixed
     */
    public function retrievePaymentIntent($payment_intent_id)
    {
        if (!$payment_intent_id) return null;
        return $this->gateway->paymentIntents->retrieve($payment_intent_id, []);
    }

    /**
     * @return mixed
     */
    public function createPaymentIntent()
    {
        $price = number_format($this->plan->price, 2, '', '');
        return $this->gateway->paymentIntents->create([
            'customer' => $this->customer->id,
            'setup_future_usage' => 'off_session',
            'amount' => (string) $price,
            'currency' => $this->plan->currency,
            'payment_method_types' => ['card'],
            "metadata" => ["plan_id" => $this->plan->id],
        ]);
    }

    /**
     * @return mixed
     */
    public function retrieveSetupIntent($setup_intent_id)
    {
        if (!$setup_intent_id) return null;
        return $this->gateway->setupIntents->retrieve($setup_intent_id, []);
    }

    /**
     * @return mixed
     */
    public function createSetupIntent()
    {
        return $this->gateway->setupIntents->create([
            'customer' => $this->customer->id,
            'usage' => 'off_session',
            'payment_method_types' => ['card'],
            "metadata" => ["plan_id" => $this->plan->id],
        ]);
    }

    /**
     * @return mixed
     */
    public function chargeExistingCard($payment_method_id = null)
    {
        // Get default payment method
        if (!$payment_method_id) {
            $payment_method = $this->getDefaultPaymentMethod();
            $payment_method_id = $payment_method->id;
        }

        $price = number_format($this->plan->price, 2, '', '');
        try {
            $payment_intent = $this->gateway->paymentIntents->create([
                'amount' => (string) $price,
                'currency' => $this->plan->currency,
                'customer' => $this->customer->id,
                'payment_method' => $payment_method_id,
                'off_session' => true,
                'confirm' => true,
                'metadata' => ['plan_id' => $this->plan->id],
            ]);
        } catch (\Stripe\Exception\CardException $e) {
            // Error code will be authentication_required if authentication is needed
            echo 'Error code is:' . $e->getError()->code;
            $payment_intent_id = $e->getError()->payment_intent->id;
            $payment_intent = $this->gateway->paymentIntents->retrieve($payment_intent_id);
        }

        return $payment_intent;
    }
}
