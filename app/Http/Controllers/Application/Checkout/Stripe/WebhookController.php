<?php

namespace App\Http\Controllers\Application\Checkout\Stripe;

use App\Http\Controllers\Controller;
use App\Http\Middleware\VerifyStripeWebhookSignature;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Notifications\User\PaymentFailed;
use App\Notifications\User\PaymentSuccess;
use App\Services\Gateways\Stripe as StripeService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe as Stripe;

class WebhookController extends Controller
{
    /**
     * Create a new WebhookController instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (get_system_setting('stripe_webhook_secret')) {
            $this->middleware(VerifyStripeWebhookSignature::class);
        }
    }

    /**
     * Handle a Stripe webhook call.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        $method = 'handle'.Str::studly(str_replace('.', '_', $payload['type']));

        if (method_exists($this, $method)) {
            $this->setMaxNetworkRetries();
            $response = $this->{$method}($payload);
            return $response;
        }

        return $this->missingMethod($payload);
    }

    /**
     * Handle deleted customer.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerDeleted(array $payload)
    {
        // Cancel the subscription if exists
        if ($user = $this->getUserByStripeId($payload['data']['object']['id'])) {
            // Set stripe customer details as null
            $user->update([
                'stripe_customer_id' => null,
                'stripe_pm_id' => null,
            ]);
        }

        return $this->successMethod();
    }

    /**
     * Handle payment intent succeeded.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handlePaymentIntentSucceeded(array $payload)
    {
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);
        $plan = $this->getPlanByPayload($payload);

        // If the user and the plan id set 
        if ($user && $plan) {
            $order = DB::transaction(function () use ($user, $plan, $payload) {
                // Create or renew subscribtion
                $user->createOrRenewSubscribtion($plan);

                // Create and store the order in database
                $amount = intval($payload['data']['object']['amount']) / 100;
                $order = Order::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'amount' => $amount,
                    'currency' => $payload['data']['object']['currency'],
                    'transaction_id' => $payload['data']['object']['id'],
                    'payment_type' => 'Stripe',
                    'payment_status' => 1,
                ]);

                return $order;
            });

            if ($order) {
                $user->notify(new PaymentSuccess($order));
            }
        }

        return $this->successMethod();
    }

    /**
     * Handle payment intent payment failed.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handlePaymentIntentPaymentFailed(array $payload)
    {
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);
        $plan = $this->getPlanByPayload($payload);

        if ($user && $plan) {
            $user->notify(new PaymentFailed());
        }

        return $this->successMethod();
    }

    /**
     * Handle payment intent succeeded.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleSetupIntentSucceeded(array $payload)
    {
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);
        $plan = $this->getPlanByPayload($payload);

        // If the user and the plan id set
        if ($user && $plan) {
            DB::transaction(function () use ($user, $plan, $payload) {
                // Create or renew subscribtion
                $user->createOrRenewSubscribtion($plan);

                // Create and store the order in database
                Order::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'amount' => 0,
                    'currency' => $plan->currency,
                    'transaction_id' => $payload['data']['object']['id'],
                    'payment_type' => 'Stripe',
                    'payment_status' => 1,
                ]);
            });
        }

        return $this->successMethod();
    }

    /**
     * Handle payment method attached.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handlePaymentMethodAttached(array $payload)
    {
        // Update user's default payment method as the attached payment method
        if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {
            $user->update([
                'stripe_pm_id' => $payload['data']['object']['id'],
            ]);
        }

        // Deduplicate cards
        $stripe = new StripeService($user);
        $payment_methods = $stripe->getAvailablePaymentMethods();
        $fingerprints = [];

        // Update default payment method
        if ($payment_methods && $payment_methods->data) {
            foreach ($payment_methods->data as $card) {
                $fingerprint = $card['card']['fingerprint'];
                if (in_array($fingerprint, $fingerprints, true)) {
                    $stripe->detachPaymentMethod($card['id']);
                } else {
                    $fingerprints[] = $fingerprint;
                }
            }
        }

        return $this->successMethod();
    }

    /**
     * Handle payment method detached.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handlePaymentMethodDetached(array $payload)
    {
        if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {
            // First disable default payment method of user
            $user->update([
                'stripe_pm_id' => null,
            ]);

            $stripe = new StripeService($user);
            $payment_methods = $stripe->getAvailablePaymentMethods();

            // Update user's default payment method if there are still existing payment methods available
            if ($payment_methods && $payment_methods->data && array_key_exists(0, $payment_methods->data)) {
                $user->update([
                    'stripe_pm_id' => $payment_methods->data[0]['id'],
                ]);
            }
        }

        return $this->successMethod();
    }

    /**
     * Get the plan instance by payload metadata.
     *
     * @param  $payload
     * @return User|null
     */
    protected function getPlanByPayload($payload)
    {
        // Check metadata exists the plan_id
        if (multi_array_key_exists(['data', 'object', 'metadata', 'plan_id'], $payload)) {
            return Plan::withTrashed()->find($payload['data']['object']['metadata']['plan_id']);
        }

        return null;
    }

    /**
     * Get the user instance by Stripe ID.
     *
     * @param  string|null  $stripeId
     * @return User|null
     */
    protected function getUserByStripeId($stripeId)
    {
        return User::where('stripe_customer_id', $stripeId)->first();
    }

    /**
     * Handle successful calls on the controller.
     *
     * @param  array  $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function successMethod($parameters = [])
    {
        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  array  $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function missingMethod($parameters = [])
    {
        return new Response;
    }

    /**
     * Set the number of automatic retries due to an object lock timeout from Stripe.
     *
     * @param  int  $retries
     * @return void
     */
    protected function setMaxNetworkRetries($retries = 3)
    {
        Stripe::setMaxNetworkRetries($retries);
    }
}
