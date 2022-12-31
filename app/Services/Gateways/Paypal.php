<?php

namespace App\Services\Gateways;

use App\Models\User;
use App\Notifications\Admin\GeneralNotification;
use App\Services\Notification\Notification;
use Illuminate\Support\Facades\Log;

class Paypal
{
    /**
     * Cancel subscription
     */
    public function cancelSubscription($user, $subscription_id)
    {
        try {
            if (get_system_setting('paypal_mode') == 'sandbox') {
                $uri = "https://api-m.sandbox.paypal.com/v1/billing/subscriptions/$subscription_id/cancel";
            } else {
                $uri = "https://api-m.paypal.com/v1/billing/subscriptions/$subscription_id/cancel";
            }
            $clientId = get_system_setting('paypal_client_id');
            $secret = get_system_setting('paypal_client_secret');

            $client = new \GuzzleHttp\Client();
            $response = $client->request(
                'POST',
                $uri,
                [
                    'headers' =>
                    [
                        'Accept' => 'application/json',
                        'Accept-Language' => 'en_US',
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode(
                        [
                            'reason' => 'Cancelled by user'
                        ]
                    ),
                    'auth' => [$clientId, $secret, 'basic']
                ]
            );
            $data = json_decode($response->getBody(), true);
            Log::info($data);
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            // Send email to admins
            $users = User::where('role', 'admin')->get();
            Notification::send($users, new GeneralNotification(
                __('Error while cancelling user\'s Paypal Subscription'), 
                __('You may need to manually cancel user\'s Paypal Subscription. User UID: :user_uid Subscription ID: :subscription_id', [
                    'user_uid' => $user->uid,
                    'subscription_id' => $subscription_id,
                ])
            ));
            return false;
        }
            
        return true;
    }

    /**
     * Activate subscription
     */
    public function activateSubscription($user, $subscription_id)
    {
        try {
            if (get_system_setting('paypal_mode') == 'sandbox') {
                $uri = "https://api-m.sandbox.paypal.com/v1/billing/subscriptions/$subscription_id/activate";
            } else {
                $uri = "https://api-m.paypal.com/v1/billing/subscriptions/$subscription_id/activate";
            }
            $clientId = get_system_setting('paypal_client_id');
            $secret = get_system_setting('paypal_client_secret');

            $client = new \GuzzleHttp\Client();
            $response = $client->request(
                'POST',
                $uri,
                [
                    'headers' =>
                    [
                        'Accept' => 'application/json',
                        'Accept-Language' => 'en_US',
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode(
                        [
                            'reason' => 'Activated by user'
                        ]
                    ),
                    'auth' => [$clientId, $secret, 'basic']
                ]
            );
            $data = json_decode($response->getBody(), true);
        } catch (\Throwable $th) {
            // Send email to admins
            $users = User::where('role', 'admin')->get();
            Notification::send($users, new GeneralNotification(
                __('Error while activating user\'s Paypal Subscription'), 
                __('You may need to manually activate user\'s Paypal Subscription. User UID: :user_uid Subscription ID: :subscription_id', [
                    'user_uid' => $user->uid,
                    'subscription_id' => $subscription_id,
                ])
            ));
            return false;
        }
            
        return true;
    }
}
