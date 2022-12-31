<?php

namespace App\Http\Controllers\Application\Checkout\Paypal;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanSubscription;
use App\Models\User;
use App\Notifications\User\PaymentFailed;
use App\Notifications\User\PaymentSuccess;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    /**
     * Handle a Paypal webhook call.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        $method = 'handle'.Str::studly(str_replace('.', '_', $payload['event_type']));

        if (method_exists($this, $method)) {
            $response = $this->{$method}($payload);
            return $response;
        }

        return $this->missingMethod($payload);
    }

    /**
     * Handle Subscription Activated.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleBillingSubscriptionActivated(array $payload)
    {
        $user = User::where('uid', $payload['resource']['custom_id'])->first();
        $plan = Plan::where('paypal_plan_id', $payload['resource']['plan_id'])->first();

        // If the user and the plan id set 
        if ($user && $plan) {
            $user->createOrRenewSubscribtion($plan, [
                'billing_agreement_id' => $payload['resource']['id'],
            ]);
        }

        return $this->successMethod();
    }

    /**
     * Handle Subscription Activated.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleBillingSubscriptionRenewed(array $payload)
    {
        $subscription = PlanSubscription::where('data->billing_agreement_id', $payload['resource']['id'])->first();

        // If the user and the plan id set 
        if ($subscription) {
            $subscription->renew();
        }

        return $this->successMethod();
    }

    /**
     * Handle Subscription Expired.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleBillingSubscriptionExpired(array $payload)
    {
        $subscription = PlanSubscription::where('data->billing_agreement_id', $payload['resource']['id'])->first();

        // Cancel the subscription
        if ($subscription && $subscription->isActive()) {
            $subscription->cancel();
        }

        return $this->successMethod();
    }

    /**
     * Handle Subscription Cancelled.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleBillingSubscriptionCancelled(array $payload)
    {
        $subscription = PlanSubscription::where('data->billing_agreement_id', $payload['resource']['id'])->first();

        // Cancel the subscription
        if ($subscription && $subscription->isActive()) {
            $subscription->cancel();
        }

        return $this->successMethod();
    }

    /**
     * Handle Subscription Suspended.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleBillingSubscriptionSuspended(array $payload)
    {
        $subscription = PlanSubscription::where('data->billing_agreement_id', $payload['resource']['id'])->first();

        // Cancel the subscription
        if ($subscription && $subscription->isActive()) {
            $subscription->cancel();
        }

        return $this->successMethod();
    }

    /**
     * Handle Subscription Payment Failed
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleBillingSubscriptionPaymentFailed(array $payload)
    {
        $subscription = PlanSubscription::where('data->billing_agreement_id', $payload['resource']['id'])->first();

        // Cancel the subscription
        if ($subscription) {
            $subscription->user->notify(new PaymentFailed());
        }

        return $this->successMethod();
    }

    /**
     * Handle Subscription Payment Completed.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handlePaymentSaleCompleted(array $payload)
    {
        $subscription = PlanSubscription::where('data->billing_agreement_id', $payload['resource']['billing_agreement_id'])->first();

        // If the user and the plan id set 
        if ($subscription) {
            $order = DB::transaction(function () use ($subscription, $payload) {
                // Create and store the order in database
                $amount = $payload['resource']['amount']['total'];
                $order = Order::create([
                    'user_id' => $subscription->user->id,
                    'plan_id' => $subscription->plan->id,
                    'amount' => $amount,
                    'currency' => $payload['resource']['amount']['currency'],
                    'transaction_id' => $payload['resource']['id'],
                    'payment_type' => 'Paypal',
                    'payment_status' => 1,
                ]);

                return $order;
            });

            if ($order) {
                $subscription->user->notify(new PaymentSuccess($order));
            }
        }

        return $this->successMethod();
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
}
