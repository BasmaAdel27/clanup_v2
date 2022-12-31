<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Group;
use App\Models\Order;
use App\Models\PlanSubscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class DashboardController extends Controller
{
    /**
     * Display the Admin Dashboard Page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events_this_month = Event::where('created_at', '>=', now()->subMonth())->published()->count();
        $events_total = Event::published()->count();
        $groups_this_month = Group::where('created_at', '>=', now()->subMonth())->count();
        $groups_total = Group::count();
        $users_this_month = User::where('created_at', '>=', now()->subMonth())->count();
        $users_total = User::count();
        $subscriptions_this_month = PlanSubscription::where('created_at', '>=', now()->subMonth())->findActive()->count();
        $subscriptions_total = PlanSubscription::findActive()->count();

        $earnings = Order::selectRaw("SUM(amount) total, DATE_FORMAT(created_at, '%Y-%m') date")
            ->groupBy('date')
            ->get()
            ->map(function ($item) {
                $item['date'] = Carbon::parse($item->date)->format('M, Y');
                return $item;
            });

        return view('admin.dashboard.index', [
            'events_this_month' => $events_this_month,
            'events_total' => $events_total,
            'groups_this_month' => $groups_this_month,
            'groups_total' => $groups_total,
            'users_this_month' => $users_this_month,
            'users_total' => $users_total,
            'subscriptions_this_month' => $subscriptions_this_month,
            'subscriptions_total' => $subscriptions_total,
            'earnings' => $earnings,
        ]);
    }
}
