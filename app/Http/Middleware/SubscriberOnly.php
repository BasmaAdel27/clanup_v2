<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SubscriberOnly
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Redirect user if there is no subscription or ended or cancelled
        $subscription = $user->subscription();

        // If there is no subscription at all
        if (!$subscription) {
            return redirect()->route('checkout.plans');
        }

        // If the subscription is active or on a grace period
        if ($subscription->isActive() || $subscription->onGrace()) {
            return $next($request);
        }

        return redirect()->route('account.organizer');
    }
}
