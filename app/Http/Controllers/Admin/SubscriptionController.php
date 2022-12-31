<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanSubscription;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SubscriptionController extends Controller
{
    /**
     * Display Admin Subscriptions Page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Subscriptions
        $subscriptions = QueryBuilder::for(PlanSubscription::class)
            ->allowedFilters([
                AllowedFilter::exact('plan_id'),
                AllowedFilter::scope('user', 'searchUser'),
                AllowedFilter::scope('status', 'getByStatus'),
            ])
            ->orderBy('id', 'desc')
            ->paginate()
            ->appends(request()->query());

        return view('admin.subscriptions.index', [
            'subscriptions' => $subscriptions
        ]);
    }

    /**
     * Cancel the subscription
     *
     * @param \App\Models\PlanSubscription $subscription
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function cancel(PlanSubscription $subscription)
    {
        // Cancel immediately
        $subscription->cancel(true);

        session()->flash('alert-success', __('Subscription cancelled'));
        return redirect()->route('admin.subscriptions');
    }
}
