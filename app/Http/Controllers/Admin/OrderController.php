<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class OrderController extends Controller
{
    /**
     * Display Admin Orders Page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = request()->query();
        if (is_array($data) && isset($data['filter']) && isset($data['filter']['id'])) {
            request()->merge(['filter' => ['id' => str_replace(get_system_setting('order_prefix'), '', $data['filter']['id'])]]);
        }

        // Get Orders
        $orders = QueryBuilder::for(Order::class)
            ->allowedFilters([
                AllowedFilter::partial('id'),
                AllowedFilter::partial('transaction_id'),
                AllowedFilter::scope('user', 'searchUser'),
                AllowedFilter::exact('plan_id'),
            ])
            ->orderBy('id', 'desc')
            ->paginate()
            ->appends($data);

        return view('admin.orders.index', [
            'orders' => $orders
        ]);
    }
}
